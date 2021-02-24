<?php

namespace App\Service;
use App\Entity\Categoria;

use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    public function getCategorias(): array
    {
        $categoriaRepo = $this->manager->getRepository(Categoria::class);
        return $categoriaRepo->findAll();
    }

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
}