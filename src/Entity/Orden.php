<?php

namespace App\Entity;

use App\Repository\OrdenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdenRepository::class)
 */
class Orden
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="ordens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Producto::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $producto;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_orden;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_pago;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estado;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getProducto(): ?Producto
    {
        return $this->producto;
    }

    public function setProducto(?Producto $producto): self
    {
        $this->producto = $producto;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getFechaOrden(): ?\DateTimeInterface
    {
        return $this->fecha_orden;
    }

    public function setFechaOrden(\DateTimeInterface $fecha_orden): self
    {
        $this->fecha_orden = $fecha_orden;

        return $this;
    }

    public function getFechaPago(): ?\DateTimeInterface
    {
        return $this->fecha_pago;
    }

    public function setFechaPago(?\DateTimeInterface $fecha_pago): self
    {
        $this->fecha_pago = $fecha_pago;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }
}
