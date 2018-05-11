<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11/05/2018
 * Time: 11:41
 */

namespace SnowTricks\AppBundle\Manager;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppManager
{

    private $container;
    private $em;

    public function __construct(ContainerInterface $container, ObjectManager $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function removeFile($file)
    {
        unlink($this->container->getParameter('pictures_directory').'/'.$file);
    }

    public function flush($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

}