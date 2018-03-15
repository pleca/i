<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="intra_document_category")
 */
class IntraDocumentCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="parent_id", type="integer")
     */
    private $parentId;

    /**
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\IntraDocuments", mappedBy="documentCategory")
     */
    private $intraDocuments;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return integer
     */
    public function getParentId()
    {
        if ($this->parentId instanceof $this){
            return $this->parentId->getId();
        }
        return $this->parentId;
    }

    /**
     * @param mixed $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return ArrayCollection|IntraDocuments[]
     */
    public function getIntraDocuments()
    {
        return $this->intraDocuments;
    }

    public function __construct()
    {
        $this->intraDocuments = new ArrayCollection();
    }

//    public function __toString()
//    {
//        return $this->name;
//    }
}