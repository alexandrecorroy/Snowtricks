<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\DashboardType;
use App\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @Route("/dashboard", name="snow_tricks_user_dashboard", methods={"GET","POST"})
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
     * @Route("/dashboard/user/{id}/deletePicture", requirements={"id" = "\d+"}, name="snow_tricks_user_dashboard_deletePicture", methods={"GET"})
     * @ParamConverter("user", class="App:User")
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
