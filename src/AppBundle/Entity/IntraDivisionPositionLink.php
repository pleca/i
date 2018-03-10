<?php

namespace AppBundle\Entity;

/**
 * IntraDivisionPositionLink
 *
 * @ORM\Table(name="intra_album")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntraAlbumRepository")
 */
class IntraDivisionPositionLink
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * todo: w xml: strategy="IDENTITY"
     */
    private $id;
    /**
     * @var integer
     */
    private $divisionPositionLinkPid;

    /**
     * @var integer
     */
    private $divisionPositionLinkDid;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set divisionPositionLinkPid
     *
     * @param integer $divisionPositionLinkPid
     *
     * @return IntraDivisionPositionLink
     */
    public function setDivisionPositionLinkPid($divisionPositionLinkPid)
    {
        $this->divisionPositionLinkPid = $divisionPositionLinkPid;

        return $this;
    }

    /**
     * Get divisionPositionLinkPid
     *
     * @return integer
     */
    public function getDivisionPositionLinkPid()
    {
        return $this->divisionPositionLinkPid;
    }

    /**
     * Set divisionPositionLinkDid
     *
     * @param integer $divisionPositionLinkDid
     *
     * @return IntraDivisionPositionLink
     */
    public function setDivisionPositionLinkDid($divisionPositionLinkDid)
    {
        $this->divisionPositionLinkDid = $divisionPositionLinkDid;

        return $this;
    }

    /**
     * Get divisionPositionLinkDid
     *
     * @return integer
     */
    public function getDivisionPositionLinkDid()
    {
        return $this->divisionPositionLinkDid;
    }
}
