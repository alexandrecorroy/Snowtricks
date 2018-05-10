<?php

namespace SnowTricks\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Trick
 *
 * @ORM\Table(name="trick")
 * @ORM\Entity(repositoryClass="SnowTricks\AppBundle\Repository\TrickRepository")
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
     * @Assert\NotBlank(
     *     message= "Name cannot be empty."
     * )
     * @Assert\Length(
     *      min = 6,
     *      max = 64,
     *      minMessage = "Name of trick must be at least {{ limit }} characters long",
     *      maxMessage = "Name of trick cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(name="name", type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=64, nullable=true)
     */
    private $slug;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message= "Description cannot be empty."
     * )
     * @Assert\Length(
     *      min = 128,
     *      max = 2048,
     *      minMessage = "Description must be at least {{ limit }} characters long",
     *      maxMessage = "Name of trick cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(name="description", type="string", length=2048)
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
     * @ORM\ManyToOne(targetEntity="SnowTricks\AppBundle\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="front_picture", type="string", length=255, nullable=true)
     * @ORM\JoinColumn(nullable=true)
     */
    private $frontPicture;

    private $frontPictureName;

    /**
     * @ORM\OneToMany(targetEntity="SnowTricks\AppBundle\Entity\Picture", mappedBy="trick", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $pictures;

    /**
     * @ORM\OneToMany(targetEntity="SnowTricks\AppBundle\Entity\Video", mappedBy="trick", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $videos;

    public function __construct()
    {
        $this->createDate = new \DateTime();
        $this->pictures = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    public function addPicture(Picture $picture)
    {
        $this->pictures[] = $picture;

        $picture->setTrick($this);

        return $this;
    }

    public function addVideo(Video $video)
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
     * @return mixed
     */
    public function getFrontPictureName()
    {
        return $this->frontPictureName;
    }

    /**
     * @param mixed $frontPictureName
     */
    public function setFrontPictureName($frontPictureName)
    {
        $this->frontPictureName = $frontPictureName;
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

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        // replace non letter or digits by -
        $slug = preg_replace('~[^\pL\d]+~u', '-', $slug);

        // transliterate
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);

        // remove unwanted characters
        $slug = preg_replace('~[^-\w]+~', '', $slug);

        // trim
        $slug = trim($slug, '-');

        // remove duplicated - symbols
        $slug = preg_replace('~-+~', '-', $slug);

        // lowercase
        $slug = strtolower($slug);

        $this->slug = $slug;
    }

}

