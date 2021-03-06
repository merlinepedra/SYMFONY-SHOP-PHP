<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\Events;
use App\Service\FileUploader;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;

use App\Entity\Image;
use App\Entity\Categoria;
use App\Entity\Producto;
use App\Entity\Usuario;
use App\Entity\Orden;
use DateTime;

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
            Events::prePersist, 
            Events::postRemove
        ];
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->cycle($args);
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->cycle($args);
    }

    public function postRemove(LifecycleEventArgs $args):void
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();


        if ($entity instanceof Usuario)
        {
            $foto = $entity->getFoto();
            $this->fileUploader->removeImageFile($foto->getFileName()); 
            $entityManager->remove($foto);
        }
        else if ($entity instanceof Image){
            $this->fileUploader->removeImageFile($entity->getFileName());  
        } 
    }

    public function cycle(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        //$entityManager = $args->getObjectManager();

        try {
            if(!$entity->getCreated()) $entity->setCreated(new DateTime()); 
        } catch (\Throwable $th) {
            //throw $th;
        }

        if($entity instanceof Usuario)
        {
            if(!$entity->getActivo()) $entity->setActivo(true);
            $roles = $entity->getRoles();
            if(count($roles) < 1) $entity->setRoles(['ROLE_USER']);
        }
        else if ($entity instanceof Orden)
        {
            if(!$entity->getEstado()) $entity->setEstado('sin pagar');
        }
        else if ($entity instanceof Image) {
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