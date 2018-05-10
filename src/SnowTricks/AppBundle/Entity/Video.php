<?php

namespace SnowTricks\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Video
 *
 * @ORM\Table(name="video")
 * @ORM\Entity(repositoryClass="SnowTricks\AppBundle\Repository\VideoRepository")
 */
class Video
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="SnowTricks\AppBundle\Entity\Trick", inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;

    /**
     * @return mixed
     */
    public function getTrick()
    {
        return $this->trick;
    }

    /**
     * @param mixed $trick
     */
    public function setTrick(Trick $trick)
    {
        $this->trick = $trick;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Video
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}

