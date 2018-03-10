<?php

namespace AppBundle\Entity;

/**
 * IntraDocuments
 *
 * @ORM\Table(name="intra_album")
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
     * @var string
     */
    private $documentFile;

    /**
     * @var string
     */
    private $documentFileTitle;

    /**
     * @var \DateTime
     */
    private $documentDateAdd;

    /**
     * @var \DateTime
     */
    private $documentDateMod;

    /**
     * @var string
     */
    private $documentType;

    /**
     * @var string
     */
    private $documentDesc;

    /**
     * @var integer
     */
    private $documentCreatorId;
    /**
     * @var integer
     */
    private $documentUserId;


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
