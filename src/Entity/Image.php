<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;



/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

     /**
     * Unmapped property to handle file uploads
     */
    private $file;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileName;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }

     /**
    * Updates the hash value to force the preUpdate and postUpdate events to fire.
    */
   public function refreshUpdated()
   {
      $this->setUpdated(new \DateTime());
   }

   public function getUpdated(): ?\DateTimeInterface
   {
       return $this->updated;
   }

   public function setUpdated(\DateTimeInterface $updated): self
   {
       $this->updated = $updated;

       return $this;
   }

   public function getFileName(): ?string
   {
       return $this->fileName;
   }

   public function setFileName(string $fileName): self
   {
       $this->fileName = $fileName;
       return $this;
   }

   public function __toString() 
   {
       return $this->getFileName();
    }

}