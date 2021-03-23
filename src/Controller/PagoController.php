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

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PagoController extends AbstractController
{
    /**
     * @Route("/changeOrdenAmounts", name="changeOrdenAmounts")
    */
    public function changeCantidadOrden(Request $request) : Response
    {
        if(!$this->getUser()){
            return $this->render('index/changeOrdenAmounts.html.twig');
        }
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
        $form->add('actualizar', SubmitType::class, array('label' => 'Pagar'));
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
                        $orden->setEstado('pagado');
                        $manager->persist($orden);
                    }
                }
            }

            $manager->flush();
            return $this->redirectToRoute('tus_compras');
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
}