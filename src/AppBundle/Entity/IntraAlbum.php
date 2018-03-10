<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IntraAlbum
 *
 * @ORM\Table(name="intra_album")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntraAlbumRepository")
 */
class IntraAlbum
{
    /**
     * @var int
     *
     * @ORM\Column(name="album_id", type="integer", unique=true, options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * todo: w xml: strategy="IDENTITY"
     */
    private $albumId;

    /**
     * @var string
     *
     * @ORM\Column(name="album_name", type="string", length=255)
     */
    private $albumName;

    /**
     * @var string
     *
     * @ORM\Column(name="album_desc", type="string", length=1023)
     */
    private $albumDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="album_image", type="string", length=255)
     */
    private $albumImage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="album_date_add", type="datetime")
     */
    private $albumDateAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="album_date_mod", type="datetime")
     */
    private $albumDateMod;

    /**
     * @var int
     *
     * @ORM\Column(name="album_creator_id", type="integer", options={"unsigned":true})
     */
    private $albumCreatorId;

    /**
     * @var int
     *
     * @ORM\Column(name="album_mod_id", type="integer", options={"unsigned":true})
     */
    private $albumModId;


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
     * Set albumId
     *
     * @param integer $albumId
     *
     * @return IntraAlbum
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
     * Set albumName
     *
     * @param string $albumName
     *
     * @return IntraAlbum
     */
    public function setAlbumName($albumName)
    {
        $this->albumName = $albumName;

        return $this;
    }

    /**
     * Get albumName
     *
     * @return string
     */
    public function getAlbumName()
    {
        return $this->albumName;
    }

    /**
     * Set albumDesc
     *
     * @param string $albumDesc
     *
     * @return IntraAlbum
     */
    public function setAlbumDesc($albumDesc)
    {
        $this->albumDesc = $albumDesc;

        return $this;
    }

    /**
     * Get albumDesc
     *
     * @return string
     */
    public function getAlbumDesc()
    {
        return $this->albumDesc;
    }

    /**
     * Set albumImage
     *
     * @param string $albumImage
     *
     * @return IntraAlbum
     */
    public function setAlbumImage($albumImage)
    {
        $this->albumImage = $albumImage;

        return $this;
    }

    /**
     * Get albumImage
     *
     * @return string
     */
    public function getAlbumImage()
    {
        return $this->albumImage;
    }

    /**
     * Set albumDateAdd
     *
     * @param \DateTime $albumDateAdd
     *
     * @return IntraAlbum
     */
    public function setAlbumDateAdd($albumDateAdd)
    {
        $this->albumDateAdd = $albumDateAdd;

        return $this;
    }

    /**
     * Get albumDateAdd
     *
     * @return \DateTime
     */
    public function getAlbumDateAdd()
    {
        return $this->albumDateAdd;
    }

    /**
     * Set albumDateMod
     *
     * @param \DateTime $albumDateMod
     *
     * @return IntraAlbum
     */
    public function setAlbumDateMod($albumDateMod)
    {
        $this->albumDateMod = $albumDateMod;

        return $this;
    }

    /**
     * Get albumDateMod
     *
     * @return \DateTime
     */
    public function getAlbumDateMod()
    {
        return $this->albumDateMod;
    }

    /**
     * Set albumCreatorId
     *
     * @param integer $albumCreatorId
     *
     * @return IntraAlbum
     */
    public function setAlbumCreatorId($albumCreatorId)
    {
        $this->albumCreatorId = $albumCreatorId;

        return $this;
    }

    /**
     * Get albumCreatorId
     *
     * @return int
     */
    public function getAlbumCreatorId()
    {
        return $this->albumCreatorId;
    }

    /**
     * Set albumModId
     *
     * @param integer $albumModId
     *
     * @return IntraAlbum
     */
    public function setAlbumModId($albumModId)
    {
        $this->albumModId = $albumModId;

        return $this;
    }

    /**
     * Get albumModId
     *
     * @return int
     */
    public function getAlbumModId()
    {
        return $this->albumModId;
    }
}
