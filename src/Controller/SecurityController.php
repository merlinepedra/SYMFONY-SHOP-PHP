<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Form\RegistroFormType;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\FileUploader;

use App\Entity\Image;

class SecurityController extends AbstractController
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    // * @IsGranted("IS_ANONYMOUS", message="Usted ya está logueado")
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        /*
        if ($this->getUser()) 
        {
            return $this->redirectToRoute('target_path');
        }
        */

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        //return $this->redirectToRoute('index');
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
     * @Route("/sing_in", name="sing_in")
     */
    public function new(Request $request, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, FileUploader $fileUploader) : Response
    {
        $user = new Usuario();

        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(RegistroFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($this->passwordEncoder->encodePassword($user, $form->get('password')->getData()));

            $fotoFile = $form->get('foto')->getData();
            if($fotoFile){
                $fotoImage = new Image();
                $fotoImage->setFile($fotoFile);
                $entityManager->persist($fotoImage);
                $user->setFoto($fotoImage);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,          // the User object you just created
                $request,
                $authenticator, // authenticator whose onAuthenticationSuccess you want to use
                'main'          // the name of your firewall in security.yaml
            );

            //return new RedirectResponse($this->urlGenerator->generate('index'));
        }

        return $this->render('security/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
