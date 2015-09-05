<?php
namespace SKCMS\TrashBinBundle\Service;

use SKCMS\TrashBinBundle\Entity\TrashBinElement;
/**
 * Description of EntityToTrash
 *
 * @author jona
 */
class TrashConverter 
{
    
    const DEFAULT_STRING = 'Missing Property';
    const DEFAULT_BOOLEAN = false;
//    const DEFAULT_ARRAY = [];
    const DEFAULT_INTEGER = 0;
    const DEFAULT_FLOAT = 0;
    const DEFAULT_DATE = '2015-08-14';
    const DEFAULT_DATETIME = '2015-08-14 15:00:00';
    
    private $defaultValues;
    
    public function __construct() {
        $this->defaultValues =[
            'string'=>self::DEFAULT_STRING,
            'boolean'=>self::DEFAULT_BOOLEAN,
            'array'=>[],
            'integer'=>self::DEFAULT_INTEGER,
            'float'=>self::DEFAULT_FLOAT,
            'date'=>new \DateTime(self::DEFAULT_DATE),
            'datetime'=>new \DateTime(self::DEFAULT_DATETIME)
            
        ];
    }
    
    public function toTrash($entity, \Doctrine\ORM\EntityManager $em)
    {
        $metaDatas = $em->getClassMetadata(get_class($entity));
        
        $trashElement = new TrashBinElement();
        foreach ($metaDatas->getFieldNames() as $fieldName)
        {
            $trashElement->addProperty($fieldName, $metaDatas->getFieldValue($entity, $fieldName));
        }

        $trashElement->setClassName(get_class($entity));
        
        foreach ($metaDatas->getIdentifierValues($entity) as $id)
        {
            $trashElement->setForeignKey($id);
        }
        
        foreach ($metaDatas->getAssociationNames($entity) as $associationName)
        {
            $associationInfo = $metaDatas->getAssociationMapping($associationName);
            if ($associationInfo['type'] == 8 || $associationInfo['type'] == 4) //Many TO Many - OneToMany
            {
                $refreshedEntity = $em->getRepository(get_class($entity))->createQueryBuilder('e')->where('e.id='.$entity->getId())->innerJoin('e.'.$associationInfo['fieldName'],'ee')->addSelect('ee')->getQuery()->getSingleResult();
                if (method_exists($entity, 'get'.ucfirst($associationName)))
                {
                    $associatedList = call_user_func([$entity,'get'.  ucfirst($associationName)]);
                    $associatedListIds = [];
                    foreach ($associatedList as $associatedEntity)
                    {
                        $associatedListIds[] = $associatedEntity->getId();
                    }

                    $trashElement->addAssociation($associationName,$associatedListIds);
                }
                
            }
            else
            {
                if (method_exists($entity, 'get'.ucfirst($associationName)))
                {
                    $associationEntity = call_user_func([$entity,'get'.  ucfirst($associationName)]);
                    $trashElement->addAssociation($associationName,$associationEntity->getId());
                }
            }
            

        }
        
        return $trashElement;
    }
    
    public function toEntity(TrashBinElement $trashElement,  \Doctrine\ORM\EntityManager $em)
    {
        $className = $trashElement->getClassName();
        $entity = new $className;
        $metaDatas = $em->getClassMetadata(get_class($entity));
        foreach ($trashElement->getProperties() as $propertyName =>$propertyValue)
        {
            if (method_exists($entity, 'set'.ucfirst($propertyName)))
            {
                call_user_func([$entity,'set'.ucfirst($propertyName)],[$propertyValue]);
            }
        }
        
        foreach ($trashElement->getAssociations() as $associationName => $associationValue)
        {
            $associationInfo = $metaDatas->getAssociationMapping($associationName);
            
            if (!is_array($associationValue))
            {
                $associatedEntity = $em->getRepository($associationInfo['targetEntity'])->findOneById($associationValue);
                if (method_exists($entity, 'set'.ucfirst($associationName)))
                {
                    call_user_func([$entity,'set'.ucfirst($associationName)],$associatedEntity);
                }
            }
            else
            {
                foreach ($associationValue as $value)
                {
                    $associatedEntity = $em->getRepository($associationInfo['targetEntity'])->find($value);
                    call_user_func([$entity,'add'.  substr(ucfirst($associationName),0,strlen($associationName)-1)],$associatedEntity);
                }
            }
        }
        
       
    }
    
}
