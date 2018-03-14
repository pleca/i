<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IntraDocuments
 *
 * @ORM\Table(name="intra_documents")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntraAlbumRepository")
 */
class IntraDocuments
{
    /**
     * @var int
     *
     * @ORM\Column(name="document_id", type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * todo: w xml: strategy="IDENTITY"
     */
    private $documentId;

    /**
     * @var IntraDocumentCategory
     * @ORM\Column(name="document_category_id", type="integer")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\IntraDocumentCategory", inversedBy="intraDocuments")})
     */
    private $documentCategoryId;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $documentFile;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $documentFileTitle;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $documentDateAdd;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $documentDateMod;

    /**
     * @var string
     * @ORM\Column(type="string", length=127)
     */
    private $documentType;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $documentDesc;

    /**
     * @var integer
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $documentCreatorId;

    /**
     * @var integer
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $documentUserId;

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->documentCategoryId;
    }

    /**
     * @param mixed $documentCategoryId
     */
    public function setCategory($documentCategoryId)
    {
        $this->documentCategoryId = $documentCategoryId;
    }

    /**
     * Set documentFile
     *
     * @param string $documentFile
     *
     * @return IntraDocuments
     */
    public function setDocumentFile($documentFile)
    {
        $this->documentFile = $documentFile;

        return $this;
    }

    /**
     * Get documentFile
     *
     * @return string
     */
    public function getDocumentFile()
    {
        return $this->documentFile;
    }

    /**
     * Set documentFileTitle
     *
     * @param string $documentFileTitle
     *
     * @return IntraDocuments
     */
    public function setDocumentFileTitle($documentFileTitle)
    {
        $this->documentFileTitle = $documentFileTitle;

        return $this;
    }

    /**
     * Get documentFileTitle
     *
     * @return string
     */
    public function getDocumentFileTitle()
    {
        return $this->documentFileTitle;
    }

    /**
     * Set documentDateAdd
     *
     * @param \DateTime $documentDateAdd
     *
     * @return IntraDocuments
     */
    public function setDocumentDateAdd($documentDateAdd)
    {
        $this->documentDateAdd = $documentDateAdd;

        return $this;
    }

    /**
     * Get documentDateAdd
     *
     * @return \DateTime
     */
    public function getDocumentDateAdd()
    {
        return $this->documentDateAdd;
    }

    /**
     * Set documentDateMod
     *
     * @param \DateTime $documentDateMod
     *
     * @return IntraDocuments
     */
    public function setDocumentDateMod($documentDateMod)
    {
        $this->documentDateMod = $documentDateMod;

        return $this;
    }

    /**
     * Get documentDateMod
     *
     * @return \DateTime
     */
    public function getDocumentDateMod()
    {
        return $this->documentDateMod;
    }

    /**
     * Set documentType
     *
     * @param boolean $documentType
     *
     * @return IntraDocuments
     */
    public function setDocumentType($documentType)
    {
        $this->documentType = $documentType;

        return $this;
    }

    /**
     * Get documentType
     *
     * @return boolean
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     * Set documentDesc
     *
     * @param string $documentDesc
     *
     * @return IntraDocuments
     */
    public function setDocumentDesc($documentDesc)
    {
        $this->documentDesc = $documentDesc;

        return $this;
    }

    /**
     * Get documentDesc
     *
     * @return string
     */
    public function getDocumentDesc()
    {
        return $this->documentDesc;
    }

    /**
     * Set documentCreatorId
     *
     * @param integer $documentCreatorId
     *
     * @return IntraDocuments
     */
    public function setDocumentCreatorId($documentCreatorId)
    {
        $this->documentCreatorId = $documentCreatorId;

        return $this;
    }

    /**
     * Get documentCreatorId
     *
     * @return integer
     */
    public function getDocumentCreatorId()
    {
        return $this->documentCreatorId;
    }

    /**
     * Set documentUserId
     *
     * @param integer $documentUserId
     *
     * @return IntraDocuments
     */
    public function setDocumentUserId($documentUserId)
    {
        $this->documentUserId = $documentUserId;

        return $this;
    }

    /**
     * Get documentUserId
     *
     * @return integer
     */
    public function getDocumentUserId()
    {
        return $this->documentUserId;
    }

    /**
     * Get documentId
     *
     * @return integer
     */
    public function getDocumentId()
    {
        return $this->documentId;
    }
}
