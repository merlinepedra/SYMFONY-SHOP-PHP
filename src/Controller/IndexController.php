<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use App\Entity\Categoria;
use App\Entity\Producto;
use App\Entity\Orden;
use app\Entity\Usuario;
use DateTime;

use App\Event\OrdenCreatedEvent;


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
                        <div class='other-box' data-color='$color'></div>
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
     * @Route("/annadir_al_carrito/{productoid}", name="carrito_plus")
     */
    public function annadir_al_carrito($productoid, EventDispatcherInterface $eventDispatcher): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $productoRepo = $manager->getRepository(Producto::class);
        $producto = $productoRepo->find($productoid);
        $user = $this->getUser();
        $cantidad = 100000000;

        $orden = new Orden();
        $orden->setUsuario($user);
        $orden->setProducto($producto);
        $orden->setCantidad($cantidad);
        $orden->setEstado('en el carrito');

        $manager->persist($orden);
        $manager->flush();

        //$eventDispatcher->dispatch(new OrdenCreatedEvent($orden));

        return $this->redirectToRoute('carrito');
        //return new Response('Ya esta en el carrito!!');
    }

    /**
     * @Route("/carrito", name="carrito")
     */
    public function ver_carrito()
    {
    }
}
