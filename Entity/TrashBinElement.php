<?php

namespace SKCMS\TrashBinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrashBinElement
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TrashBinElement
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="className", type="string", length=255)
     */
    private $className;

    /**
     * @var string
     *
     * @ORM\Column(name="foreignKey", type="string", length=255)
     */
    private $foreignKey;

    /**
     * @var array
     *
     * @ORM\Column(name="properties", type="array")
     */
    private $properties;
    
    /**
     * @var array
     *
     * @ORM\Column(name="associations", type="array")
     */
    private $associations;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDeletion", type="datetime")
     */
    private $dateDeletion;


    public function __construct()
    {
        $this->dateDeletion = new \DateTime();
        $this->properties = [];
        $this->associations = [];
    }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set className
     *
     * @param string $className
     * @return TrashBinElement
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get className
     *
     * @return string 
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Set foreignKey
     *
     * @param string $foreignKey
     * @return TrashBinElement
     */
    public function setForeignKey($foreignKey)
    {
        $this->foreignKey = $foreignKey;

        return $this;
    }

    /**
     * Get foreignKey
     *
     * @return string 
     */
    public function getForeignKey()
    {
        return $this->foreignKey;
    }

    /**
     * Set associations
     *
     * @param array $associations
     * @return TrashBinElement
     */
    public function setAssociations($associations)
    {
        $this->associations = $associations;

        return $this;
    }

    /**
     * Get associations
     *
     * @return array 
     */
    public function getAssociations()
    {
        return $this->associations;
    }
    /**
     * Set properties
     *
     * @param array $properties
     * @return TrashBinElement
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * Get properties
     *
     * @return array 
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Set dateDeletion
     *
     * @param \DateTime $dateDeletion
     * @return TrashBinElement
     */
    public function setDateDeletion($dateDeletion)
    {
        $this->dateDeletion = $dateDeletion;

        return $this;
    }

    /**
     * Get dateDeletion
     *
     * @return \DateTime 
     */
    public function getDateDeletion()
    {
        return $this->dateDeletion;
    }
    
    public function addProperty($key,$value)
    {
        $this->properties[$key]=$value;
    }
    
    public function addAssociation($key,$value)
    {
        $this->associations[$key]=$value;
    }
}
