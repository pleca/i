<?php

namespace AppBundle\Entity;

/**
 * IntraDivisionPosition
 *
 * @ORM\Table(name="intra_division_position")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntraAlbumRepository")
 */
class IntraDivisionPosition
{
    /**
     * @var int
     *
     * @ORM\Column(name="position_id", type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * todo: w xml: strategy="IDENTITY"
     */
    private $positionId;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $positionName;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $positionStatus;


    /**
     * Set positionDivisionId
     *
     * @param integer $positionDivisionId
     *
     * @return IntraDivisionPosition
     */
    public function setPositionDivisionId($positionDivisionId)
    {
        $this->positionDivisionId = $positionDivisionId;

        return $this;
    }

    /**
     * Get positionDivisionId
     *
     * @return integer
     */
    public function getPositionDivisionId()
    {
        return $this->positionDivisionId;
    }

    /**
     * Set positionName
     *
     * @param string $positionName
     *
     * @return IntraDivisionPosition
     */
    public function setPositionName($positionName)
    {
        $this->positionName = $positionName;

        return $this;
    }

    /**
     * Get positionName
     *
     * @return string
     */
    public function getPositionName()
    {
        return $this->positionName;
    }

    /**
     * Set positionStatus
     *
     * @param boolean $positionStatus
     *
     * @return IntraDivisionPosition
     */
    public function setPositionStatus($positionStatus)
    {
        $this->positionStatus = $positionStatus;

        return $this;
    }

    /**
     * Get positionStatus
     *
     * @return boolean
     */
    public function getPositionStatus()
    {
        return $this->positionStatus;
    }

    /**
     * Get positionId
     *
     * @return integer
     */
    public function getPositionId()
    {
        return $this->positionId;
    }
}
