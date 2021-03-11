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
use app\Entity\Usuario;
use DateTime;

use App\Event\OrdenCreatedEvent;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IndexController extends AbstractController
{
    public function tabla($collection)
    {
        $response = "";
        for ($i=0; $i < 2; $i++) { 
            $columnas = "";
            $precios = "";
            for ($j=0; $j < 4; $j++) { 
                $index = 4*$i + $j;
                if($index > count($collection) - 1) continue;
                $p = $collection[$index];
                $nombre = $p->getNombre();
                $color = $p->getFotos()[0];
                $id = $p->getId();
                $precio = $p->getPrecioUnidad();

                $nombre .= $nombre.' '.$nombre.' '.$nombre.' '.$nombre;
                $columnas .= "
                <td>
                    <div class='container'>
                        <div class='other-box-index' data-color='$color'></div>
                        <p><a class='pname' href='productoView/$id'>$nombre</a></p>
                        
                    </div>
                </td>";
                $precios .= "<td><p class='precio h3 font-weight-bolder'>$precio.00 $</p></td>";
            }
            $response .= "<tr>$columnas</tr><tr>$precios</tr>";
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

        $novedades = $productoRepo->getUploadSince(10, 8);
        $populares = $productoRepo->getMostPopular(8);
        $valorados = $productoRepo->getMostExpensive(8);
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
    public function productoView($productoid): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $productoRepo = $manager->getRepository(Producto::class);

        if($productoid == -1) $producto = $productoRepo->findAll()[0];
        else $producto = $productoRepo->find($productoid);

        return $this->render('index/productoView.html.twig', [
            'producto' => $producto
        ]);
    }

    /**
     * @Route("/carrito_plus/{productoid}/{cantidad}", name="carrito_plus")
     */
    public function carrito_plus($productoid, $cantidad, EventDispatcherInterface $eventDispatcher): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $productoRepo = $manager->getRepository(Producto::class);
        $producto = $productoRepo->find($productoid);
        $user = $this->getUser();

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

    /**
     * @Route("/changeOrdenAmounts", name="changeOrdenAmounts")
    */
    public function changeCantidadOrden(Request $request) : Response
    {
        $manager = $this->getDoctrine()->getManager();
        $ordenRepo = $manager->getRepository(Orden::class);
        $enCarrito = $ordenRepo->getCarrito($this->getUser()->getId());

        $defaultData = [];
        $form = $this->createFormBuilder();

        foreach ($enCarrito as $orden) {
            $name = strval($orden->getId());
            $defaultData[$name] = $orden->getCantidad();
            $form->add(
                $name, 
                NumberType::class, 
                array('required' => false));
        }
        $form->add('actualizar', SubmitType::class);
        $form->setData($defaultData);
        $form = $form->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();        

            foreach ($data as $key => $value) {
                if($value)
                {
                    $orden = $ordenRepo->find((int)$key);
                    if ($orden) {
                        $orden->setCantidad($value);
                        $manager->persist($orden);
                    }
                }
            }

            $manager->flush();
            return $this->redirectToRoute('ver_carrito');
        }

        return $this->render('index/changeOrdenAmounts.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cancelOrden/{ordenId}", name="cancel_orden")
     */
    public function cancel($ordenId) : Response
    {
        $manager = $this->getDoctrine()->getManager();
        $ordenRepo = $manager->getRepository(Orden::class);
        $orden =$ordenRepo->find($ordenId);
        if($orden) $orden->setEstado('cancelado');
        $manager->flush();

        return $this->redirectToRoute('changeOrdenAmounts');
    }

    /**
     * @Route("/pagar", name="pagar")
     */
    public function pagar() : Response
    {
        $manager = $this->getDoctrine()->getManager();
        $ordenRepo = $manager->getRepository(Orden::class);
        $enCarrito = $ordenRepo->getCarrito($this->getUser()->getId());

        foreach ($enCarrito as $orden) {
            $orden->setEstado('pagado');
            $manager->persist($orden);
        }

        $manager->flush();
        return $this->redirectToRoute('tus_compras');
    }

    /**
     * @Route("/tusCompras", name="tus_compras")
     */
    public function tus_compras() : Response
    {
        return $this->render("index/tusCompras.html.twig");
    }

    /**
     * @Route("/verCarrito", name="ver_carrito")
     */
    public function verCarrito() : Response
    {
        return $this->render('index/verCarrito.html.twig');
    }
}