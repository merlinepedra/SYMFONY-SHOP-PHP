<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Migrations\Version\ExecutionResult;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * @ORM\Entity(repositoryClass=CategoriaRepository::class)
 */
class Categoria
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Producto::class, mappedBy="categoria")
     */
    private $productos;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class, inversedBy="subcategorias")
     */
    private $padre;

    /**
     * @ORM\OneToMany(targetEntity=Categoria::class, mappedBy="padre")
     */
    private $subcategorias;

    public function validate(ExecutionContextInterface $context, $payload)
    {
        if($this->soyAncestro($this->getPadre()))
        {
            $context->buildViolation('Esta categoria no puede ser mi padre!')
            ->atPath('padre')
            ->addViolation();
        }
    }

    private function soyAncestro($node)
    {
        if($node === null) return false;
        if($node->getId() === $this->getId()) return true;
        else return soyAncestro($node->getPadre());
    }

    public function __construct()
    {
        $this->productos = new ArrayCollection();
        $this->subcategorias = new ArrayCollection();
        $this->padre = null;
    }

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

    /**
     * @return Collection|Producto[]
     */
    public function getproducto(): Collection
    {
        return $this->productos;
    }

    public function addproducto(Producto $producto): self
    {
        if (!$this->productos->contains($producto)) {
            $this->productos[] = $producto;
            $producto->setCategoria($this);
        }

        return $this;
    }

    public function removeproducto(Producto $producto): self
    {
        if ($this->productos->removeElement($producto)) {
            // set the owning side to null (unless already changed)
            if ($producto->getCategoria() === $this) {
                $producto->setCategoria(null);
            }
        }

        return $this;
    }

    public function getPadre(): ?self
    {
        return $this->padre;
    }

    public function setPadre(?self $padre): self
    {
        $this->padre = $padre;
        if($padre != null) $padre->addSubcategoria($this);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSubcategorias(): Collection
    {
        return $this->subcategorias;
    }

    public function addSubcategoria(self $subcategoria): self
    {
        if (!$this->subcategorias->contains($subcategoria)) {
            $this->subcategorias[] = $subcategoria;
            $subcategoria->setPadre($this);
        }

        return $this;
    }

    public function removeSubcategoria(self $subcategoria): self
    {
        if ($this->subcategorias->removeElement($subcategoria)) {
            // set the owning side to null (unless already changed)
            if ($subcategoria->getPadre() === $this) {
                $subcategoria->setPadre(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nombre;
    }
}
