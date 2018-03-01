<?php

namespace AppBundle\Entity;

/**
 * IntraUserPositionLink
 */
class IntraUserPositionLink
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $divisionLinkUid;

    /**
     * @var integer
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
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
