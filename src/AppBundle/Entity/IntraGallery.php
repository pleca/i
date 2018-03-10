<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IntraGallery
 *
 * @ORM\Table(name="intra_gallery")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntraGalleryRepository")
 */
class IntraGallery
{
    /**
     * @var int
     *
     * @ORM\Column(name="gallery_id", type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * todo: w xml: strategy="IDENTITY"
     */
    private $galleryId;

    /**
     * @var int
     *
     * @ORM\Column(name="album_id", type="integer")
     */
    private $albumId;

    /**
     * @var string
     *
     * @ORM\Column(name="gallery_images", type="string", length=255)
     */
    public $galleryImages;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="gallery_date_add", type="datetime")
     */
    private $galleryDateAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="gallery_mod_add", type="datetime")
     */
    private $galleryDateMod;

    /**
     * @var int
     *
     * @ORM\Column(name="gallery_creator_id", type="integer")
     */
    private $galleryCreatorId;

    /**
     * @var int
     *
     * @ORM\Column(name="gallery_mod_id", type="integer")
     */
    private $galleryModId;

    /**
     * Set galleryId
     *
     * @param integer $galleryId
     *
     * @return IntraGallery
     */
    public function setGalleryId($galleryId)
    {
        $this->galleryId = $galleryId;

        return $this;
    }

    /**
     * Get galleryId
     *
     * @return int
     */
    public function getGalleryId()
    {
        return $this->galleryId;
    }

    /**
     * Set albumId
     *
     * @param integer $albumId
     *
     * @return IntraGallery
     */
    public function setAlbumId($albumId)
    {
        $this->albumId = $albumId;

        return $this;
    }

    /**
     * Get albumId
     *
     * @return int
     */
    public function getAlbumId()
    {
        return $this->albumId;
    }


    /**
     * Set galleryImages
     *
     * @param string $galleryImages
     *
     * @return IntraGallery
     */
    public function setGalleryImages($galleryImages)
    {
        $this->galleryImages = $galleryImages;

        return $this;
    }

    /**
     * Get galleryImages
     *
     * @return string
     */
    public function getGalleryImages()
    {
        return $this->galleryImages;
    }

    /**
     * Set galleryDateAdd
     *
     * @param \DateTime $galleryDateAdd
     *
     * @return IntraGallery
     */
    public function setGalleryDateAdd($galleryDateAdd)
    {
        $this->galleryDateAdd = $galleryDateAdd;

        return $this;
    }

    /**
     * Get galleryDateAdd
     *
     * @return \DateTime
     */
    public function getGalleryDateAdd()
    {
        return $this->galleryDateAdd;
    }

    /**
     * Set galleryDateMod
     *
     * @param \DateTime $galleryDateMod
     *
     * @return IntraGallery
     */
    public function setGalleryDateMod($galleryDateMod)
    {
        $this->galleryDateMod = $galleryDateMod;

        return $this;
    }

    /**
     * Get galleryDateMod
     *
     * @return \DateTime
     */
    public function getGalleryDateMod()
    {
        return $this->galleryDateMod;
    }

    /**
     * Set galleryCreatorId
     *
     * @param integer $galleryCreatorId
     *
     * @return IntraGallery
     */
    public function setGalleryCreatorId($galleryCreatorId)
    {
        $this->galleryCreatorId = $galleryCreatorId;

        return $this;
    }

    /**
     * Get galleryCreatorId
     *
     * @return int
     */
    public function getGalleryCreatorId()
    {
        return $this->galleryCreatorId;
    }

    /**
     * Set galleryModId
     *
     * @param integer $galleryModId
     *
     * @return IntraGallery
     */
    public function setGalleryModId($galleryModId)
    {
        $this->galleryModId = $galleryModId;

        return $this;
    }

    /**
     * Get galleryModId
     *
     * @return int
     */
    public function getGalleryModId()
    {
        return $this->galleryModId;
    }
}
