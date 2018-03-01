<?php

namespace AppBundle\Entity;

/**
 * IntraDivisionPosition
 */
class IntraDivisionPosition
{
    /**
     * @var integer
     */
    private $positionDivisionId;

    /**
     * @var string
     */
    private $positionName;

    /**
     * @var boolean
     */
    private $positionStatus;

    /**
     * @var integer
     */
    private $positionId;


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
