<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IntraEvents
 *
 * @ORM\Table(name="entity_intra_events")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Entity\IntraEventsRepository")
 */
class IntraEvents
{
    /**
     * @var int
     *
     * @ORM\Column(name="news_id", type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * todo: w xml: strategy="IDENTITY"
     */
    private $newsId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="news_date_add", type="datetime")
     */
    private $newsDateAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="news_date_mod", type="datetime")
     */
    private $newsDateMod;

    /**
     * @var string
     *
     * @ORM\Column(name="news_title", type="string", length=255)
     */
    private $newsTitle;

    /**
     * @var int
     *
     * @ORM\Column(name="news_creator_id", type="integer")
     */
    private $newsCreatorId;

    /**
     * @var int
     *
     * @ORM\Column(name="news_user_id", type="integer")
     */
    private $newsUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="news_text", type="text")
     */
    private $newsText;

    /**
     * @var string
     *
     * @ORM\Column(name="news_short_text", type="string", length=255)
     */
    private $newsShortText;

    /**
     * @var string
     *
     * @ORM\Column(name="news_image", type="string", length=127)
     */
    private $newsImage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="news_start", type="datetime")
     */
    private $newsStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="news_end", type="datetime")
     */
    private $newsEnd;

    /**
     * @var string
     *
     * @ORM\Column(name="news_type", type="string", length=255)
     */
    private $newsType;

    /**
     * @var int
     *
     * @ORM\Column(name="news_status", type="integer")
     */
    private $newsStatus;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set newsId
     *
     * @param integer $newsId
     *
     * @return IntraEvents
     */
    public function setNewsId($newsId)
    {
        $this->newsId = $newsId;

        return $this;
    }

    /**
     * Get newsId
     *
     * @return int
     */
    public function getNewsId()
    {
        return $this->newsId;
    }

    /**
     * Set newsDateAdd
     *
     * @param \DateTime $newsDateAdd
     *
     * @return IntraEvents
     */
    public function setNewsDateAdd($newsDateAdd)
    {
        $this->newsDateAdd = $newsDateAdd;

        return $this;
    }

    /**
     * Get newsDateAdd
     *
     * @return \DateTime
     */
    public function getNewsDateAdd()
    {
        return $this->newsDateAdd;
    }

    /**
     * Set newsDateMod
     *
     * @param \DateTime $newsDateMod
     *
     * @return IntraEvents
     */
    public function setNewsDateMod($newsDateMod)
    {
        $this->newsDateMod = $newsDateMod;

        return $this;
    }

    /**
     * Get newsDateMod
     *
     * @return \DateTime
     */
    public function getNewsDateMod()
    {
        return $this->newsDateMod;
    }

    /**
     * Set newsTitle
     *
     * @param string $newsTitle
     *
     * @return IntraEvents
     */
    public function setNewsTitle($newsTitle)
    {
        $this->newsTitle = $newsTitle;

        return $this;
    }

    /**
     * Get newsTitle
     *
     * @return string
     */
    public function getNewsTitle()
    {
        return $this->newsTitle;
    }

    /**
     * Set newsCreatorId
     *
     * @param integer $newsCreatorId
     *
     * @return IntraEvents
     */
    public function setNewsCreatorId($newsCreatorId)
    {
        $this->newsCreatorId = $newsCreatorId;

        return $this;
    }

    /**
     * Get newsCreatorId
     *
     * @return int
     */
    public function getNewsCreatorId()
    {
        return $this->newsCreatorId;
    }

    /**
     * Set newsUserId
     *
     * @param integer $newsUserId
     *
     * @return IntraEvents
     */
    public function setNewsUserId($newsUserId)
    {
        $this->newsUserId = $newsUserId;

        return $this;
    }

    /**
     * Get newsUserId
     *
     * @return int
     */
    public function getNewsUserId()
    {
        return $this->newsUserId;
    }

    /**
     * Set newsText
     *
     * @param string $newsText
     *
     * @return IntraEvents
     */
    public function setNewsText($newsText)
    {
        $this->newsText = $newsText;

        return $this;
    }

    /**
     * Get newsText
     *
     * @return string
     */
    public function getNewsText()
    {
        return $this->newsText;
    }

    /**
     * Set newsShortText
     *
     * @param string $newsShortText
     *
     * @return IntraEvents
     */
    public function setNewsShortText($newsShortText)
    {
        $this->newsShortText = $newsShortText;

        return $this;
    }

    /**
     * Get newsShortText
     *
     * @return string
     */
    public function getNewsShortText()
    {
        return $this->newsShortText;
    }

    /**
     * Set newsImage
     *
     * @param string $newsImage
     *
     * @return IntraEvents
     */
    public function setNewsImage($newsImage)
    {
        $this->newsImage = $newsImage;

        return $this;
    }

    /**
     * Get newsImage
     *
     * @return string
     */
    public function getNewsImage()
    {
        return $this->newsImage;
    }

    /**
     * Set newsStart
     *
     * @param \DateTime $newsStart
     *
     * @return IntraEvents
     */
    public function setNewsStart($newsStart)
    {
        $this->newsStart = $newsStart;

        return $this;
    }

    /**
     * Get newsStart
     *
     * @return \DateTime
     */
    public function getNewsStart()
    {
        return $this->newsStart;
    }

    /**
     * Set newsEnd
     *
     * @param \DateTime $newsEnd
     *
     * @return IntraEvents
     */
    public function setNewsEnd($newsEnd)
    {
        $this->newsEnd = $newsEnd;

        return $this;
    }

    /**
     * Get newsEnd
     *
     * @return \DateTime
     */
    public function getNewsEnd()
    {
        return $this->newsEnd;
    }

    /**
     * Set newsType
     *
     * @param string $newsType
     *
     * @return IntraEvents
     */
    public function setNewsType($newsType)
    {
        $this->newsType = $newsType;

        return $this;
    }

    /**
     * Get newsType
     *
     * @return integer
     */
    public function getNewsType()
    {
        return $this->newsType;
    }

    /**
     * Set newsStatus
     *
     * @param integer $newsStatus
     *
     * @return IntraEvents
     */
    public function setNewsStatus($newsStatus)
    {
        $this->newsStatus = $newsStatus;

        return $this;
    }

    /**
     * Get newsStatus
     *
     * @return int
     */
    public function getNewsStatus()
    {
        return $this->newsStatus;
    }
    /**
     * @var integer
     */
    private $newsVacationType;

    /**
     * @var boolean
     */
    private $newsAllday = '1';


    /**
     * Set newsVacationType
     *
     * @param integer $newsVacationType
     *
     * @return IntraEvents
     */
    public function setNewsVacationType($newsVacationType)
    {
        $this->newsVacationType = $newsVacationType;

        return $this;
    }

    /**
     * Get newsVacationType
     *
     * @return integer
     */
    public function getNewsVacationType()
    {
        return $this->newsVacationType;
    }

    /**
     * Set newsAllday
     *
     * @param boolean $newsAllday
     *
     * @return IntraEvents
     */
    public function setNewsAllday($newsAllday)
    {
        $this->newsAllday = $newsAllday;

        return $this;
    }

    /**
     * Get newsAllday
     *
     * @return boolean
     */
    public function getNewsAllday()
    {
        return $this->newsAllday;
    }
    /**
     * @var string
     */
    private $newsUrl;


    /**
     * Set newsUrl
     *
     * @param string $newsUrl
     *
     * @return IntraEvents
     */
    public function setNewsUrl($newsUrl)
    {
        $this->newsUrl = $newsUrl;

        return $this;
    }

    /**
     * Get newsUrl
     *
     * @return string
     */
    public function getNewsUrl()
    {
        return $this->newsUrl;
    }
}
