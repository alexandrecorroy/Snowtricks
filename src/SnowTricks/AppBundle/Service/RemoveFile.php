<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11/05/2018
 * Time: 13:41
 */

namespace SnowTricks\AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class RemoveFile
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function remove($file)
    {
        unlink($this->container->getParameter('pictures_directory').'/'.$file);
    }
}
