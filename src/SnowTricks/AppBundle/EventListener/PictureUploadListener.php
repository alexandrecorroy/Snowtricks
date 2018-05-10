<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 19/04/2018
 * Time: 21:46
 */

namespace SnowTricks\AppBundle\EventListener;
use SnowTricks\AppBundle\Service\FileUploader;
use SnowTricks\AppBundle\Entity\Picture;
use SnowTricks\AppBundle\Entity\Trick;
use SnowTricks\AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;


class PictureUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        if ($entity instanceof Picture) {
            $file = $entity->getFile();

            if (!$file instanceof UploadedFile) {
                return;
            }

            $fileName = $this->uploader->upload($file);
            $entity->setFile($fileName);
        }


        if ($entity instanceof Trick) {
            $file = $entity->getFrontPicture();

            if (!$file instanceof UploadedFile) {
                return;
            }

            $fileName = $this->uploader->upload($file);
            $entity->setFrontPicture($fileName);
        }

        if ($entity instanceof User) {
            $file = $entity->getPicture();

            if (!$file instanceof UploadedFile) {
                return;
            }

            $fileName = $this->uploader->upload($file);
            $entity->setPicture($fileName);
        }

    }
}