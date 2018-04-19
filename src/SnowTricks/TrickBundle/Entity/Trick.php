<?php

namespace SnowTricks\TrickBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trick
 *
 * @ORM\Table(name="trick")
 * @ORM\Entity(repositoryClass="SnowTricks\TrickBundle\Repository\TrickRepository")
 */
class Trick
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
     *
     * @ORM\Column(name="name", type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1024)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetimetz")
     */
    private $createDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="edit_date", type="datetimetz", nullable=true)
     */
    private $editDate;

    /**
     * @ORM\ManyToOne(targetEntity="SnowTricks\TrickBundle\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="front_picture", type="string", length=255)
     * @ORM\JoinColumn(nullable=false)
     */
    private $frontPicture;

    /**
     * @ORM\OneToMany(targetEntity="SnowTricks\TrickBundle\Entity\Picture", mappedBy="trick", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $pictures;

    /**
     * @ORM\OneToMany(targetEntity="SnowTricks\TrickBundle\Entity\Video", mappedBy="trick", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $videos;

    public function __construct()
    {
        $this->createDate = new \DateTime();
        $this->pictures = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    public function addPictures(Picture $picture)
    {
        $this->pictures[] = $picture;

        $picture->setTrick($this);

        return $this;
    }

    public function addVideos(Video $video)
    {
        $this->videos[] = $video;

        $video->setTrick($this);

        return $this;
    }

    public function removePicture(Picture $picture)
    {
        $this->pictures->removeElement($picture);
    }

    public function removeVideo(Video $video)
    {
        $this->videos->removeElement($video);
    }

    public function getPictures()
    {
        return $this->pictures;
    }

    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getFrontPicture()
    {
        return $this->frontPicture;
    }

    /**
     * @param string $frontPicture
     */
    public function setFrontPicture($frontPicture)
    {
        $this->frontPicture = $frontPicture;
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
     * Set name
     *
     * @param string $name
     *
     * @return Trick
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Trick
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Trick
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set editDate
     *
     * @param \DateTime $editDate
     *
     * @return Trick
     */
    public function setEditDate($editDate)
    {
        $this->editDate = $editDate;

        return $this;
    }

    /**
     * Get editDate
     *
     * @return \DateTime
     */
    public function getEditDate()
    {
        return $this->editDate;
    }
}

