<?php

namespace SnowTricks\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SnowTricks\AppBundle\Entity\User;
use SnowTricks\AppBundle\Form\Type\DashboardType;
use SnowTricks\AppBundle\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/dashboard", name="snow_tricks_user_dashboard")
     * @Method({"GET","POST"})
     */
    public function dashboardAction(Request $request, UserManager $userManager)
    {
        $user = $this->getUser();

        $savePictureUser = $user->getPicture();

        $form = $this->createForm(DashboardType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userManager->updateUser($savePictureUser, $user);

            $this->addFlash(
                'notice',
                'Your account has been updated !'
            );

            return $this->redirectToRoute('snow_tricks_user_dashboard');
        }

        return $this->render('User/dashboard.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/dashboard/user/{id}/deletePicture", requirements={"id" = "\d+"}, name="snow_tricks_user_dashboard_deletePicture")
     * @ParamConverter("user", class="SnowTricksAppBundle:User")
     * @Method("GET")
     */
    public function deleteUserPictureAction(User $user, UserManager $userManager)
    {

        $userManager->deleteAvatar($user);

        $this->addFlash(
            'notice',
            'Your avatar has been deleted !'
        );

        return $this->redirectToRoute('snow_tricks_user_dashboard');
    }
}
