<?php

namespace SnowTricks\UserBundle\Controller;

use SnowTricks\UserBundle\Entity\User;
use SnowTricks\UserBundle\Form\ForgotPasswordType;
use SnowTricks\UserBundle\Form\RegistrationType;
use SnowTricks\UserBundle\Form\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('snow_tricks_homepage');
        }

        // Le service authentication_utils permet de récupérer le nom d'utilisateur
        // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
        // (mauvais mot de passe par exemple)
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('@SnowTricksUser/Security/login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }

    public function registerAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $user->setToken($this::generateToken($user));

                $user->setPassword($this->encodePassword($user, $user->getPassword()));

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();


                $this->sendMail($user, 'Confirmer votre compte', 'registration');

                $this->addFlash(
                    'notice',
                    'An email sent to you for verify account !'
                );

                return $this->redirectToRoute('snow_tricks_homepage');
            }
        }

        return $this->render('@SnowTricksUser/Security/registration.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    // Encodage password
    public function encodePassword(User $user, $password)
    {

        $factory = $this->get('security.encoder_factory');

        $encoder = $factory->getEncoder($user);
        return $encoder->encodePassword($password, $user->getSalt());
    }

    public static function generateToken(User $user)
    {
        $data = $user->getEmail().uniqid().microtime();
        return hash('sha512', $data);
    }

    public function sendMail(User $user, $subject, $template)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('contact@snowtricks.com')
            ->setTo($user->getEmail())
            ->setBody($this->renderView(
                '@SnowTricksUser/Emails/'.$template.'.html.twig',
                array('username' => $user->getUsername(), 'token' => $user->getToken())),
                'text/html')
        ;
        $this->get('mailer')->send($message);
    }

    public function tokenVerificationAction($token)
    {
        $request = new Request();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneBy(['token' => $token]);

        if(!$user->isEnabled())
        {
            $user->setIsActive(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'notice',
                'Your account is active !'
            );

        }
        else
        {
            $this->addFlash(
                'notice',
                'Account already active !'
            );

        }

        return $this->redirectToRoute('snow_tricks_homepage');
    }

    public function forgotPasswordAction(Request $request)
    {

        $form = $this->createForm(ForgotPasswordType::class);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $repository = $this->getDoctrine()->getRepository(User::class);

                $user = $repository->findOneBy(
                    array('username' => $request->get('username'))
                );

                if($user)
                {

                    $user->setToken($this::generateToken($user));

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    $this->sendMail($user, 'Reset your password', 'forgot_password');
                }

                $request->getSession()->getFlashBag()->add('notice', 'An email send to you for reset password !');

                return $this->redirectToRoute('snow_tricks_user_forgotPassword');
            }
        }

        return $this->render('@SnowTricksUser/Security/forgot_password.html.twig', array(
            'form' => $form->createView(),
        ));


    }

    public function resetPasswordAction(Request $request)
    {

        // on récupére le userToken
        $token = $request->get('token');

        $repository = $this->getDoctrine()->getRepository(User::class);
        $userToken = $repository->findOneBy(
            array('token' => $token)
        );

        // si aucun token correspondant
        if(!$userToken)
        {
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
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                // on récupére le user via email du form
                $repository = $this->getDoctrine()->getRepository(User::class);

                $user = $repository->findOneBy(
                    array('email' => $request->get('email'))
                );

                // compare si userToken et user === username
                if($user->getUsername()===$userToken->getUsername())
                {

                    // on modifie le mot de passe de user
                    $user->setPassword($this->encodePassword($user, $request->get('password')));

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                }

                // renvoi sur home avec message
                $this->addFlash(
                    'notice',
                    'Password has been changed !'
                );
                return $this->redirectToRoute('snow_tricks_homepage');
            }
        }

        return $this->render('@SnowTricksUser/Security/reset_password.html.twig', array(
            'form' => $form->createView(),
        ));


    }
}