<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Categoria;
use App\Entity\Producto;
use App\Entity\Orden;
use App\Entity\Usuario;
use App\Entity\Calificacion;
use DateTime;

use App\Event\OrdenCreatedEvent;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IndexController extends AbstractController
{
    public function tabla($collection, $cols)
    {
        //$filas = (count($collection)/$cols) + 1;
        $filas = count($collection);
        
        $response = "";
        for ($i=0; $i < $filas; $i++) { 
            $columnas = "";
            $precios = "";
            for ($j=0; $j < $cols; $j++) { 
                $index = $cols*$i + $j;
                if($index > count($collection) - 1) break;
                $p = $collection[$index];
                $nombre = $p->getNombre();
                $color = $p->getFotos()[0];
                $id = $p->getId();
                $precio = $p->getPrecioUnidad();

                
                $columnas .= "
                <td>
                    <div class='index-box' data-color='$color'></div>
                    <p><a class='pname' href='productoView/$id'>$nombre</a></p>
                    <span class='precio'>$precio.00 $</span>
                </td>";
                $precios .= "<td><p class='precio h3 font-weight-bolder'>$precio.00 $</p></td>";
            }
            //$response .= "<tr>$columnas</tr><tr>$precios</tr>";
            $response .= "<tr>$columnas</tr>";
            if($index > count($collection) - 1) break;
        }
        return new Response($response);
    }
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $productoRepo = $manager->getRepository(Producto::class);
        $categoriaRepo = $manager->getRepository(Categoria::class);

        $novedades = $productoRepo->getUploadSince(10, 10);
        $populares = $productoRepo->getMostPopular(10);
        $valorados = $productoRepo->getMostExpensive(10);
        $ventas = [];

        for ($i=0; $i < count($populares); $i++) { 
            $ventas[$i] = $populares[$i]['SUM(cantidad)'];        
            $populares[$i] =  $productoRepo->find($populares[$i]['producto_id']);
        }


        return $this->render('index/index.html.twig', [
            'novedades' => $novedades,
            'populares' => $populares,
            'valorados' => $valorados,
            'ventas' => $ventas
        ]);
    }

    /**
     * @Route("/productoView/{productoid}", name="productoView")
     */
    public function productoView($productoid, Request $request): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $productoRepo = $manager->getRepository(Producto::class);
        $producto = $productoRepo->find($productoid);

        $defaultData = ['cantidad' => 1];
        $form = $this->createFormBuilder($defaultData)
        ->add('cantidad', NumberType::class)
        ->add('calificacion', NumberType::class, array(
            'mapped' => false,
            'required' => false,
        ))
        ->add('agregar', SubmitType::class)
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $this->getUser();

            if(array_key_exists('calificacion', $form->getData()))
            {
                $calificacion = $form->getData()['calificacion'];
                $new_rate = new Calificacion();
                $new_rate->setUsuario($user);
                $new_rate->setProducto($producto);
                $new_rate->setValue($calificacion);
                $manager->persist($new_rate);
            }

            $cantidad = $form->getData()['cantidad'];
            $ordenRepo = $manager->getRepository(Orden::class);
    
            $preOrden = $ordenRepo->findBy([
                'usuario'=> $user->getId(),
                'producto' => $productoid,
                'estado' => 'en el carrito'
            ]);
    
            if($preOrden && count($preOrden) > 0)
            {
                $preOrden = $preOrden[0];
                $preCantidad = $preOrden->getCantidad();
                $preOrden->setCantidad($preCantidad + $cantidad);
                $manager->persist($preOrden);
                $manager->flush();
                return $this->redirectToRoute('changeOrdenAmounts');
            }

            $orden = new Orden();
            $orden->setUsuario($user);
            $orden->setProducto($producto);
            $orden->setCantidad($cantidad);
            $orden->setEstado('en el carrito');
    
            $manager->persist($orden);
            $manager->flush();
    
            //$eventDispatcher->dispatch(new OrdenCreatedEvent($orden));
    
            return $this->redirectToRoute('changeOrdenAmounts');
        }

        return $this->render('index/productoview.html.twig', [
            'form' => $form->createView(),
            'producto' => $producto
        ]);
    }

    /**
     * @Route("/tusCompras", name="tus_compras")
     */
    public function tus_compras() : Response
    {
        return $this->render("index/tusCompras.html.twig");
    }

    public function createPanelResponse(Categoria $categoria, $parent) : Response
    {
        return new Response($this->createPanel($categoria, $parent, 0));
    }

    public function createPanel(Categoria $categoria, $parent, $deep) : string
    {
        $manager = $this->getDoctrine()->getManager();
        $productoRepo = $manager->getRepository(Producto::class);
        $products = $productoRepo->getByCategory($categoria);
        $subcategorias = $categoria->getSubcategorias();
        $categoriaName = $categoria->getNombre();

        if(count($subcategorias) == 0 && count($products) == 0) return "";

        $productsHTML = "";
        $subsHTML = "";

        foreach ($subcategorias as $sub) {
            $subsHTML .= $this->createPanel($sub, $categoriaName, $deep + 1);
        }

        foreach ($products as $prod) {
            $proName = $prod->getNombre();
            $proId = $prod->getId();
            $url = $this->generateUrl('productoView', array(
                'productoid'=> $proId
            ));
            $productsHTML .= "
            <a href='$url' class='list-group-item sidebar_texto' data-parent='#$categoriaName'>
            $proName</a>";
        }

        $class = '';
        if($deep == 1) $class = 'list-group-submenu';
        if($deep > 1) 
        {
            $d = $deep - 1;
            $class = "list-group-submenu list-group-submenu-$d";
        }
        
        $response = " 
        <a href='#$categoriaName' class='list-group-item sidebar_texto' data-toggle='collapse' 
        data-parent='#$parent'>
        $categoriaName <i class='fa fa-caret-down sidebar_carets'></i></a>
        <div class='collapse $class' id='$categoriaName'>
            $subsHTML
            $productsHTML
        </div>
        ";
        
        return $response;
    }
}