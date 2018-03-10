<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IntraDivisionDepartmentLink
 *
 * @ORM\Table(name="intra_division_department_link")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntraAlbumRepository")
 */
class IntraDivisionDepartmentLink
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", unique=true, options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * todo: w xml: strategy="IDENTITY"
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $divisionDepartmentLinkDepartmentId;

    /**
     * @var integer
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $divisionDepartmentLinkDivisionId;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

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
