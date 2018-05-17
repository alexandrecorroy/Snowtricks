<?php

namespace SnowTricks\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SnowTricks\AppBundle\Entity\User;
use SnowTricks\AppBundle\Form\Type\ForgotPasswordType;
use SnowTricks\AppBundle\Form\Type\RegistrationType;
use SnowTricks\AppBundle\Form\Type\ResetPasswordType;
use SnowTricks\AppBundle\Manager\UserManager;
use SnowTricks\AppBundle\Service\Mailer;
use SnowTricks\AppBundle\Service\PasswordEncoder;
use SnowTricks\AppBundle\Service\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{

    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('snow_tricks_homepage');
        }

        // Le service authentication_utils permet de récupérer le nom d'utilisateur
        // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
        // (mauvais mot de passe par exemple)
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('@SnowTricksApp/User/Security/login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }

    /**
     * @Route("/registration", name="snow_tricks_user_registration")
     * @Method({"GET","POST"})
     */
    public function registerAction(Request $request, Mailer $mailer, TokenGenerator $tokenGenerator, UserManager $userManager, PasswordEncoder $passwordEncoder)
    {
        $user = $userManager->initUser();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userManager->createUser($user);

            $this->addFlash(
                'notice',
                'An email sent to you for verify account !'
            );

            return $this->redirectToRoute('snow_tricks_homepage');
        }

        return $this->render('@SnowTricksApp/User/Security/registration.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/token/{token}", name="snow_tricks_user_tokenVerification")
     * @Method("GET")
     */
    public function tokenVerificationAction($token, UserManager $userManager)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneBy(['token' => $token]);

        $userManager->activeUser($user);

        return $this->redirectToRoute('snow_tricks_homepage');
    }

    /**
     * @Route("/forgot_password", name="snow_tricks_user_forgotPassword")
     * @Method({"GET","POST"})
     */
    public function forgotPasswordAction(Request $request, UserManager $userManager)
    {
        $form = $this->createForm(ForgotPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $repository = $this->getDoctrine()->getRepository(User::class);

            $user = $repository->findOneBy(
                array('username' => $request->get('username'))
            );

            $userManager->resetPassword($user);

            $this->addFlash(
                'notice',
                'An email send to you for reset password !'
            );

            return $this->redirectToRoute('snow_tricks_user_forgotPassword');
        }

        return $this->render('@SnowTricksApp/User/Security/forgot_password.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/reset_password/{token}", name="snow_tricks_user_resetPassword")
     * @Method({"GET","POST"})
     */
    public function resetPasswordAction(Request $request, PasswordEncoder $passwordEncoder, UserManager $userManager)
    {

        $repository = $this->getDoctrine()->getRepository(User::class);
        $userToken = $repository->findOneBy(
            array('token' => $request->get('token'))
        );

        // si aucun token correspondant
        if (!$userToken) {
            // renvoi sur home avec message
            $this->addFlash(
                'notice',
                'No ask for change password for you !'
            );
            return $this->redirectToRoute('snow_tricks_homepage');
        }

        // creation du formulaire
        $form = $this->createForm(ResetPasswordType::class);

        // si formulaire rempli et valide
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // on récupére le user via email du form
            $repository = $this->getDoctrine()->getRepository(User::class);

            $user = $repository->findOneBy(
                array('email' => $request->get('email'))
            );

            $userManager->updatePassword($user, $userToken, $request->get('password'));

            // renvoi sur home avec message
            $this->addFlash(
                'notice',
                'Password has been changed !'
            );
            return $this->redirectToRoute('snow_tricks_homepage');
        }

        return $this->render('@SnowTricksApp/User/Security/reset_password.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
