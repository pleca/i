<?php

namespace AppBundle\Entity;

/**
 * IntraDivision
 *
 * @ORM\Table(name="intra_division")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntraAlbumRepository")
 */
class IntraDivision
{
    /**
     * @var int
     *
     * @ORM\Column(name="division_id", type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * todo: w xml: strategy="IDENTITY"
     */
    private $divisionId;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $divisionName;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $divisionStatus;

    /**
     * Set divisionName
     *
     * @param string $divisionName
     *
     * @return IntraDivision
     */
    public function setDivisionName($divisionName)
    {
        $this->divisionName = $divisionName;

        return $this;
    }

    /**
     * Get divisionName
     *
     * @return string
     */
    public function getDivisionName()
    {
        return $this->divisionName;
    }

    /**
     * Set divisionStatus
     *
     * @param boolean $divisionStatus
     *
     * @return IntraDivision
     */
    public function setDivisionStatus($divisionStatus)
    {
        $this->divisionStatus = $divisionStatus;

        return $this;
    }

    /**
     * Get divisionStatus
     *
     * @return boolean
     */
    public function getDivisionStatus()
    {
        return $this->divisionStatus;
    }

    /**
     * Get divisionId
     *
     * @return integer
     */
    public function getDivisionId()
    {
        return $this->divisionId;
    }
}
