<?php

namespace SnowTricks\UserBundle\Controller;

use SnowTricks\AppBundle\Controller\AppController;
use SnowTricks\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $user);

        $formBuilder
            ->add('username',      TextType::class)
            ->add('email',    EmailType::class)
            ->add('password', PasswordType::class)
            ->add('save',      SubmitType::class)
        ;

        $form = $formBuilder->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $user->setToken($this::generateToken($user));
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();


                $this->sendMail($user, 'Confirmer votre compte', 'registration');

                $request->getSession()->getFlashBag()->add('notice', 'Un email a été envoyé pour valider votre compte !');

                return $this->redirectToRoute('snow_tricks_homepage');
            }
        }

        return $this->render('@SnowTricksUser/Security/registration.html.twig', array(
            'form' => $form->createView(),
        ));

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
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneBy(['token' => $token]);

        $user->setStatus(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('snow_tricks_homepage');
    }
}
