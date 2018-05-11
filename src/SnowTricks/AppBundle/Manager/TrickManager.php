<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11/05/2018
 * Time: 11:35
 */

namespace SnowTricks\AppBundle\Manager;


use SnowTricks\AppBundle\Entity\Trick;

class TrickManager
{

    private $trick;

    public function __construct(Trick $trick)
    {
        $this->trick = $trick;
    }

    public function initTrick()
    {
        return $this->trick;
    }

    public function addPictures($pictures)
    {
        foreach($pictures as $picture)
        {

            $this->trick->addPicture($picture);
        }

    }

    public function addVideos($videos)
    {
        foreach($videos as $video)
        {

            $this->trick->addVideo($video);
        }
    }

}