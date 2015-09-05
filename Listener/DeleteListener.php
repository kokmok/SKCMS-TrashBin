<?php
namespace SKCMS\TrashBinBundle\Listener;

/**
 * Description of DeleteListener
 *
 * @author jona
 */
class DeleteListener 
{
    protected $reader;
    protected $converter;
    public function __construct(\Doctrine\Common\Annotations\Reader $reader, \SKCMS\TrashBinBundle\Service\TrashConverter $converter) {
        $this->reader = $reader;
        $this->converter = $converter;
    }
    public function preRemove(\Doctrine\ORM\Event\LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        
        if ($this->isTrashBinable(get_class($entity)))
        {
            $trashElement = $this->converter->toTrash($entity,$em);
            $em->getUnitOfWork()->scheduleForInsert($trashElement);
        }
    }
    
    protected function isTrashBinable($className)
    {
        $object = new \ReflectionClass($className);
        $annotations = $this->reader->getClassAnnotations($object);
        foreach ($annotations as $configuration) 
        {
            if ($configuration instanceof \SKCMS\TrashBinBundle\Annotation\TrashBinable)
            {
                return true;
            }
        }
        return false;
    }
}
