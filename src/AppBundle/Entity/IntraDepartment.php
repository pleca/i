<?php

namespace AppBundle\Entity;

/**
 * IntraDepartment
 */
class IntraDepartment
{

    /**
     * @var string
     */
    private $departmentName;

    /**
     * @var boolean
     */
    private $departmentStatus;

    /**
     * @var integer
     */
    private $departmentId;

    /**
     * @var integer
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
