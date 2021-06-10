<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11/05/2018
 * Time: 11:35
 */

namespace App\Manager;

use App\Entity\Trick;
use App\Service\RemoveFile;
use App\Service\Slugger;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TrickManager
{
    private $trick;
    private $em;
    private $removeFile;
    private $slugger;

    public function __construct(Trick $trick, EntityManagerInterface $em, RemoveFile $removeFile, Slugger $slugger)
    {
        $this->trick = $trick;
        $this->em = $em;
        $this->removeFile = $removeFile;
        $this->slugger = $slugger;
    }

    public function initTrick()
    {
        return $this->trick;
    }

    public function addPictures(Trick $trick)
    {
        $pictures = $trick->getPictures();

        foreach ($pictures as $picture) {
            $this->trick->addPicture($picture);
        }

        return $trick;
    }

    public function addVideos(Trick $trick)
    {
        $videos = $trick->getVideos();

        foreach ($videos as $video)
        {
            $trick->addVideo($video);
        }

        return $trick;
    }

    public function saveTrick(Trick $trick)
    {
        $this->em->persist($trick);
        $this->em->flush();
    }

    public function saveOldTrick(Trick $trick)
    {
        $this->initPictures($trick);
        return $trick;
    }

    public function initPictures(Trick $trick)
    {
        $trick->setFrontPictureName($trick->getFrontPicture());

        foreach ($trick->getPictures() as $picture) {
            $picture->setFileName($picture->getFile());
        }
    }

    public function comparePicturesTrick(Trick $oldTrick, Trick $newTrick)
    {
        $originalPictures = new ArrayCollection();

        foreach ($oldTrick->getPictures() as $picture) {
            $originalPictures->add($picture);
        }

        $files = $newTrick->getPictures();

        //remove delete pictures
        foreach ($originalPictures as $picture) {
            if (false === $files->contains($picture)) {
                $this->removeFile->remove($picture->getFileName());
            }
        }

        //set filename to file on unchange files and add news pictures
        foreach ($files as $file) {
            if ($file->getId() !== null && $file->getFile() === null) {
                $file->setFile($file->getFileName());
            } elseif ($file->getId() === null && $file->getFile() !== null) {
                $newTrick->addPicture($file);
            } elseif ($file->getId() !== null && $file->getFile() !== null) {
                $newTrick->addPicture($file);
                $this->removeFile->remove($file->getFileName());
            }
        }

        //set filename to frontPicture on unchange files and add news pictures
        if ($newTrick->getFrontPicture() === null && $newTrick->getFrontPictureName() !== null) {
            $newTrick->setFrontPicture($newTrick->getFrontPictureName());
        } else {
            if ($newTrick->getFrontPictureName() !== null) {
                $this->removeFile->remove($newTrick->getFrontPictureName());
            }
        }

        return $newTrick;
    }

    public function editTrick(Trick $oldTrick, Trick $newTrick)
    {
        $trick = $this->comparePicturesTrick($oldTrick, $newTrick);

        $trick = $this->addVideos($trick);

        $trick->setEditDate(new \DateTime());

        $trick->setSlug($this->slugger->slugify($trick->getName()));

        $this->saveTrick($trick);
    }

    public function createTrick(Trick $trick)
    {
        $trick = $this->addPictures($trick);

        $trick = $this->addVideos($trick);

        $trick->setSlug($this->slugger->slugify($trick->getName()));

        $this->saveTrick($trick);
    }

    public function deleteTrick(Trick $trick)
    {
        foreach ($trick->getPictures() as $picture) {
            $this->removeFile->remove($picture->getFile());
        }

        // delete frontPicture from server
        if ($trick->getFrontPicture() !== null) {
            $this->removeFile->remove($trick->getFrontPicture());
        }

        $this->em->remove($trick);
        $this->em->flush();
    }

    public function jsonResponseOnTricks($last_trick_id)
    {
        $tricks = $this->em->getRepository('Trick')->findOtherTricks($last_trick_id);

        return $tricks;

    }

    public function listFirstTricks()
    {

        $tricks = $this->em->getRepository('App:Trick')->listTricks();

        if (null === $tricks) {
            throw new NotFoundHttpException("No tricks found.");
        }

        return $tricks;
    }


}
