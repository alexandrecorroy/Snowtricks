<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16/04/2018
 * Time: 14:24
 */

namespace SnowTricks\TrickBundle\Controller;


use SnowTricks\AppBundle\Service\FileUploader;
use SnowTricks\TrickBundle\Entity\Picture;
use SnowTricks\TrickBundle\Entity\Trick;
use SnowTricks\TrickBundle\Form\TrickType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TrickController extends Controller
{

    public function addAction(Request $request, FileUploader $fileUploader = null)
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $files = $trick->getPictures();
                $videos = $trick->getVideos();


                foreach($files as $file)
                {

                    $trick->addPictures($file);
                }

                foreach($videos as $video)
                {

                    $trick->addVideos($video);
                }


                $em->persist($trick);
                $em->flush();

                $this->addFlash(
                    'notice',
                    'Trick Added !'
                );

                return $this->redirectToRoute('snow_tricks_homepage');
            }
        }

        return $this->render('@SnowTricksTrick/Trick/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
