<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * IntraDepartment
 *
 * @ORM\Table(name="intra_department")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntraAlbumRepository")
 */
class IntraDepartment
{
    /**
     * @var int
     *
     * @ORM\Column(name="department_id", type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * todo: w xml: strategy="IDENTITY"
     */
    private $departmentId;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $departmentName;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $departmentStatus;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $departmentDirectorUid;

    /**
     * Set departmentName
     *
     * @param string $departmentName
     *
     * @return IntraDepartment
     */
    public function setDepartmentName($departmentName)
    {
        $this->departmentName = $departmentName;

        return $this;
    }

    /**
     * Get departmentName
     *
     * @return string
     */
    public function getDepartmentName()
    {
        return $this->departmentName;
    }

    /**
     * Set departmentStatus
     *
     * @param boolean $departmentStatus
     *
     * @return IntraDepartment
     */
    public function setDepartmentStatus($departmentStatus)
    {
        $this->departmentStatus = $departmentStatus;

        return $this;
    }
    /**
     * Set departmentDirectorUid
     *
     * @param integer $departmentDirectorUid
     *
     * @return IntraDepartment
     */
    public function setDepartmentDirectorUid($departmentDirectorUid)
    {
        $this->departmentDirectorUid = $departmentDirectorUid;

        return $this;
    }

    /**
     * Get departmentStatus
     *
     * @return boolean
     */
    public function getDepartmentStatus()
    {
        return $this->departmentStatus;
    }

    /**
     * Get departmentDirectorUid
     *
     * @return integer
     */
    public function getDepartmentDirectorUid()
    {
        return $this->departmentDirectorUid;
    }
    /**
     * Get departmentId
     *
     * @return integer
     */
    public function getDepartmentId()
    {
        return $this->departmentId;
    }
}
