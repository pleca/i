<?php

namespace AppBundle\Entity;

/**
 * IntraVacation
 */
class IntraVacation
{
    /**
     * @var integer
     */
    private $vacationRemPlanDays;

    /**
     * @var integer
     */
    private $vacationRemDays;

    /**
     * @var integer
     */
    private $vacationDays;

    /**
     * @var integer
     *
     * @ORM\Column(name="vacation_uid", type="integer")
     * @ORM\Id
     */
    private $vacationUid;


    /**
     * Set vacationRemPlanDays
     *
     * @param integer $vacationRemPlanDays
     *
     * @return IntraVacation
     */
    public function setVacationRemPlanDays($vacationRemPlanDays)
    {
        $this->vacationRemPlanDays = $vacationRemPlanDays;

        return $this;
    }

    /**
     * Get vacationRemPlanDays
     *
     * @return integer
     */
    public function getVacationRemPlanDays()
    {
        return $this->vacationRemPlanDays;
    }

    /**
     * Set vacationRemDays
     *
     * @param integer $vacationRemDays
     *
     * @return IntraVacation
     */
    public function setVacationRemDays($vacationRemDays)
    {
        $this->vacationRemDays = $vacationRemDays;

        return $this;
    }

    /**
     * Get vacationRemDays
     *
     * @return integer
     */
    public function getVacationRemDays()
    {
        return $this->vacationRemDays;
    }

    /**
     * Set vacationDays
     *
     * @param integer $vacationDays
     *
     * @return IntraVacation
     */
    public function setVacationDays($vacationDays)
    {
        $this->vacationDays = $vacationDays;

        return $this;
    }

    /**
     * Get vacationDays
     *
     * @return integer
     */
    public function getVacationDays()
    {
        return $this->vacationDays;
    }

    /**
     * Set vacationUid
     *
     * @param integer $vacationUid
     *
     * @return IntraVacation
     */
    public function setVacationUid($vacationUid)
    {
        $this->vacationUid = $vacationUid;

        return $this;
    }

    /**
     * Get vacationUid
     *
     * @return int
     */
    public function getVacationUid()
    {
        return $this->vacationUid;
    }
}
