<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\Events;
use App\Service\FileUploader;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;

use App\Entity\Image;

class DataBaseSubscriber implements EventSubscriber
{
    private $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postUpdate,
            Events::prePersist
        ];
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->upload($args);
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->upload($args);
    }

    public function upload(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        // perhaps you only want to act on some "Product" entity
        if ($entity instanceof Image) {
            $file = $entity->getFile();
            $entity->refreshUpdated();
            if($file){
                $fotoFileName = $this->fileUploader->upload($file);
                $entity->setFileName($fotoFileName);
                $entity->setFile(null);
            }
        }
    }
}