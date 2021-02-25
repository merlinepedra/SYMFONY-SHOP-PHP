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
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $categoriaRepo = $manager->getRepository(Categoria::class);
        $productoRepo = $manager->getRepository(Producto::class);

        return $this->render('index/index.html.twig');
    }

    /**
     * @Route("/productoview/{productoid}", name="productoview")
     */
    public function productoview($productoid): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $categoriaRepo = $manager->getRepository(Categoria::class);
        $productoRepo = $manager->getRepository(Producto::class);


        $categorias = $categoriaRepo->findAll();
        $user = $this->getUser();

        if($productoid == -1) $producto = $productoRepo->findAll()[0];
        else $producto = $productoRepo->find($productoid);

        return $this->render('index/productoview.html.twig', [
            'categorias' => $categorias,
            'producto' => $producto,
            'user'=> $user
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
        
        //print_r('Estamos metiendo en el carrito el producto: ');

        $orden = new Orden();
        $orden->setUsuario($user);
        $orden->setProducto($producto);
        $orden->setCantidad($cantidad);
        $orden->setEstado('sin pagar');
        $orden->setFechaOrden(new DateTime('now'));

        $manager->persist($orden);
        $manager->flush();

        //$eventDispatcher->dispatch(new OrdenCreatedEvent($orden));

        return $this->redirectToRoute('index');
        //return new Response('Ya esta en el carrito!!');
    }
}
