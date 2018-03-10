<?php

namespace AppBundle\Entity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * IntraUser
 * @ORM\Table(name="intra_user")
 * @ORM\Entity
 * @UniqueEntity(fields={"userLogin"}, message="Taki login już istnieje!")
 */
class IntraUser implements UserInterface
{
    /**
     * @var integer
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userId;

    /**
     * @var string
     * @ORM\Column(name="user_login", type="string", length=255, unique=true)
     */
    private $userLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", length=127)
     */
    private $userName;

    /**
     * @var string
     * @ORM\Column(name="user_lastname", type="string", length=127)
     */
    private $userLastname;

    /**
     * @var string
     *
     * @ORM\Column(name="user_email", type="string", length=127)
     */
    private $userEmail;

    /**
     * @var string
     * @ORM\Column(name="user_password", type="string", length=127)
     */
    private $userPassword;

    /**
     * @var string
     * @ORM\Column(name="user_type", type="string", length=127)
     */
    private $userType;

    /**
     * @var integer
     * @ORM\Column(name="user_active", type="integer")
     */
    private $userActive;

    /**
     * @var \DateTime
     * @ORM\Column(name="user_date_add", type="datetime")
     */
    private $userDateAdd;

    /**
     * @var \DateTime
     * @ORM\Column(name="user_date_last_login", type="datetime")
     */
    private $userDateLastlogin;

    /**
     * @var integer
     * @ORM\Column(name="user_vacation_days", type="integer")
     */
    private $userVacationDays;

    /**
     * Set userLogin
     *
     * @param string $userLogin
     *
     * @return IntraUser
     */
    public function setUserLogin($userLogin)
    {
        $this->userLogin = $userLogin;

        return $this;
    }

    /**
     * Get userLogin
     *
     * @return string
     */
    public function getUserLogin()
    {
        return $this->userLogin;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return IntraUser
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set userEmail
     *
     * @param string $userEmail
     *
     * @return IntraUser
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * Get userEmail
     *
     * @return string
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * Set userPassword
     *
     * @param string $userPassword
     *
     * @return IntraUser
     */
    public function setUserPassword($userPassword)
    {
        $this->userPassword = $userPassword;

        return $this;
    }

    /**
     * Get userPassword
     *
     * @return string
     */
    public function getUserPassword()
    {
        return $this->userPassword;
    }

    /**
     * Set userType
     *
     * @param boolean $userType
     *
     * @return IntraUser
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get userType
     *
     * @return boolean
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Set userActive
     *
     * @param boolean $userActive
     *
     * @return IntraUser
     */
    public function setUserActive($userActive)
    {
        $this->userActive = $userActive;

        return $this;
    }

    /**
     * Get userActive
     *
     * @return boolean
     */
    public function getUserActive()
    {
        return $this->userActive;
    }

    /**
     * Set userDateAdd
     *
     * @param \DateTime $userDateAdd
     *
     * @return IntraUser
     */
    public function setUserDateAdd($userDateAdd)
    {
        $this->userDateAdd = $userDateAdd;

        return $this;
    }

    /**
     * Get userDateAdd
     *
     * @return \DateTime
     */
    public function getUserDateAdd()
    {
        return $this->userDateAdd;
    }

    /**
     * Set userDateLastlogin
     *
     * @param \DateTime $userDateLastlogin
     *
     * @return IntraUser
     */
    public function setUserDateLastlogin($userDateLastlogin)
    {
        $this->userDateLastlogin = $userDateLastlogin;

        return $this;
    }

    /**
     * Get userDateLastlogin
     *
     * @return \DateTime
     */
    public function getUserDateLastlogin()
    {
        return $this->userDateLastlogin;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    public function getRoles()
    {
        return array($this->userType);
    }

    public function getPassword()
    {
        return $this->userPassword;
    }
    public function getSalt()
    {
        return $this->userId;
    }
    public function eraseCredentials()
    {
        return $this->userId;
    }

    /**
     * Set userLastname
     *
     * @param string $userLastname
     *
     * @return IntraUser
     */
    public function setUserLastname($userLastname)
    {
        $this->userLastname = $userLastname;

        return $this;
    }

    /**
     * Get userLastname
     *
     * @return string
     */
    public function getUserLastname()
    {
        return $this->userLastname;
    }

    /**
     * Set userVacationDays
     *
     * @param integer $userVacationDays
     *
     * @return IntraUser
     */
    public function setUserVacationDays($userVacationDays)
    {
        $this->userVacationDays = $userVacationDays;

        return $this;
    }

    /**
     * Get userVacationDays
     *
     * @return integer
     */
    public function getUserVacationDays()
    {
        return $this->userVacationDays;
    }
}
