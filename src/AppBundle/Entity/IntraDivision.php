<?php

namespace AppBundle\Entity;

/**
 * IntraDivision
 */
class IntraDivision
{
    /**
     * @var string
     */
    private $divisionName;

    /**
     * @var boolean
     */
    private $divisionStatus;

    /**
     * @var integer
     */
    private $divisionId;

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
