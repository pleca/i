<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IntraUserPositionLink
 *
 * @ORM\Table(name="intra_user_position_link",
 *     indexes={@ORM\Index(name="user_position_idx", columns={"id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntraAlbumRepository")
 */
class IntraUserPositionLink
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
     * @ORM\Column(name="division_link_uid", type="integer", options={"unsigned":true})
     */
    private $divisionLinkUid;

    /**
     * @var integer
     * @ORM\Column(name="division_link_pid", type="integer", options={"unsigned":true})
     */
    private $divisionLinkPid;

    /**
     * Set divisionLinkUid
     *
     * @param integer $divisionLinkUid
     *
     * @return IntraUserPositionLink
     */
    public function setDivisionLinkUid($divisionLinkUid)
    {
        $this->divisionLinkUid = $divisionLinkUid;

        return $this;
    }

    /**
     * Get divisionLinkUid
     *
     * @return integer
     */
    public function getDivisionLinkUid()
    {
        return $this->divisionLinkUid;
    }

    /**
     * Set divisionLinkPid
     *
     * @param integer $divisionLinkPid
     *
     * @return IntraUserPositionLink
     */
    public function setDivisionLinkPid($divisionLinkPid)
    {
        $this->divisionLinkPid = $divisionLinkPid;

        return $this;
    }

    /**
     * Get divisionLinkPid
     *
     * @return integer
     */
    public function getDivisionLinkPid()
    {
        return $this->divisionLinkPid;
    }
    /**
     * Get divisionId
     *d
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

}
