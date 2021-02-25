<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=ProductoRepository::class)
 */
class Producto
{
    public function __construct()
    {
        $this->fecha_creacion = new DateTime('now');
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $fotos = [];

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class, inversedBy="productos")
     */
    private $categoria;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_creacion;

    /**
     * @ORM\Column(type="float")
     */
    private $precio_unidad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFotos(): ?array
    {
        return $this->fotos;
    }

    public function setFotos(?array $fotos): self
    {
        $this->fotos = $fotos;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTime
    {
        return $this->fecha_creacion;
    }

    /*
    public function setFechaCreacion(\DateTime $fecha_creacion): self
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }
    */

    public function getPrecioUnidad(): ?float
    {
        return $this->precio_unidad;
    }

    public function setPrecioUnidad(float $precio_unidad): self
    {
        $this->precio_unidad = $precio_unidad;

        return $this;
    }
}
