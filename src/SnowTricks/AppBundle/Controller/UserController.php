<?php

namespace SnowTricks\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SnowTricks\AppBundle\Entity\User;
use SnowTricks\AppBundle\Form\DashboardType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/dashboard", name="snow_tricks_user_dashboard")
     */
    public function dashboardAction(Request $request)
    {
        $user = $this->getUser();

        $savePictureUser = $user->getPicture();

        $form = $this->createForm(DashboardType::class, $user);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid()) {


                if($user->getPicture() != null && $savePictureUser != null)
                {
                    $file = $this->container->getParameter('pictures_directory').'/'.$savePictureUser;

                    unlink($file);
                }
                elseif($savePictureUser != null)
                {
                    $user->setPicture($savePictureUser);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash(
                    'notice',
                    'Your account has been updated !'
                );

                return $this->redirectToRoute('snow_tricks_user_dashboard');
            }
        }

        return $this->render('@SnowTricksApp/User/dashboard.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/dashboard/user/{id}/deletePicture", requirements={"id" = "\d+"}, name="snow_tricks_user_dashboard_deletePicture")
     * @ParamConverter("user", class="SnowTricksAppBundle:User")
     */
    public function deleteUserPictureAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();

        unlink($this->container->getParameter('pictures_directory').'/'.$user->getPicture());
        $user->setPicture(null);

        $em->persist($user);
        $em->flush();

        $this->addFlash(
            'notice',
            'Your avatar has been deleted !'
        );

        return $this->redirectToRoute('snow_tricks_user_dashboard');
    }
}
