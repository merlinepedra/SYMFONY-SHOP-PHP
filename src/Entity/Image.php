<?php

namespace App\Entity;
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

    /**
     * @ORM\OneToOne(targetEntity=Usuario::class, mappedBy="foto", cascade={"persist", "remove"})
     */
    private $usuario;

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

   public function getUsuario(): ?Usuario
   {
       return $this->usuario;
   }

   public function setUsuario(?Usuario $usuario): self
   {
       // unset the owning side of the relation if necessary
       if ($usuario === null && $this->usuario !== null) {
           $this->usuario->setFoto(null);
       }

       // set the owning side of the relation if necessary
       if ($usuario !== null && $usuario->getFoto() !== $this) {
           $usuario->setFoto($this);
       }

       $this->usuario = $usuario;

       return $this;
   }


   public function __toString() 
   {
       return $this->getFileName();
    }

}