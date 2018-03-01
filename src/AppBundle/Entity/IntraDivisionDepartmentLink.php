<?php

namespace AppBundle\Entity;

/**
 * IntraDivisionDepartmentLink
 */
class IntraDivisionDepartmentLink
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var integer
     */
    private $divisionDepartmentLinkDepartmentId;

    /**
     * @var integer
     */
    private $divisionDepartmentLinkDivisionId;


    /**
     * Set divisionDepartmentLinkDepartmentId
     *
     * @param integer $divisionDepartmentLinkDepartmentId
     *
     * @return IntraDivisionDepartmentLink
     */
    public function setDivisionDepartmentLinkDepartmentId($divisionDepartmentLinkDepartmentId)
    {
        $this->divisionDepartmentLinkDepartmentId = $divisionDepartmentLinkDepartmentId;

        return $this;
    }

    /**
     * Get divisionDepartmentLinkDepartmentId
     *
     * @return integer
     */
    public function getDivisionDepartmentLinkDepartmentId()
    {
        return $this->divisionDepartmentLinkDepartmentId;
    }

    /**
     * Set divisionDepartmentLinkDivisionId
     *
     * @param integer $divisionDepartmentLinkDivisionId
     *
     * @return IntraDivisionDepartmentLink
     */
    public function setDivisionDepartmentLinkDivisionId($divisionDepartmentLinkDivisionId)
    {
        $this->divisionDepartmentLinkDivisionId = $divisionDepartmentLinkDivisionId;

        return $this;
    }

    /**
     * Get divisionDepartmentLinkDivisionId
     *
     * @return integer
     */
    public function getDivisionDepartmentLinkDivisionId()
    {
        return $this->divisionDepartmentLinkDivisionId;
    }
}
