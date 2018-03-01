<?php

namespace AppBundle\Entity;

/**
 * IntraDivisionPositionLink
 */
class IntraDivisionPositionLink
{
    /**
     * @var integer
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
