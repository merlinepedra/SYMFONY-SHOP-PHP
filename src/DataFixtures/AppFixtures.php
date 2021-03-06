<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Producto;
use App\Entity\Usuario;
use App\Entity\Orden;
use App\Entity\Categoria;
use App\Entity\Image;
use DateTime;
use Doctrine\ORM\Query\Expr\Math;
use Symfony\Component\Validator\Constraints\Length;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadImages($manager);
        $this->loadProductos($manager);
        $this->loadUsers($manager);
        $this->loadOrdenes($manager);
    }

    private function loadProductos(ObjectManager $manager)
    {
        $this->loadCategorias($manager);

        foreach ($this->getProductoData() as [$nombre, $descripcion, $categoria, $created, $precio_unidad, $fotos]) {
            $producto = new Producto();
            $producto->setNombre($nombre);
            $producto->setDescripcion($descripcion);
            $producto->setCategoria($categoria);
            //$producto->setCreated($created);
            $producto->setPrecioUnidad($precio_unidad);
            $producto->setFotos($fotos);

            $manager->persist($producto);
            $this->addReference($nombre, $producto);
        }

        $manager->flush();
    }

    private function getProductoData() : array
    {
        //[$nombre, $descripcion, $categoria, $created, $precio_unidad, $fotos]
        $productos = [];
        foreach ($this->getProductosName() as $i => $name) {
            $productos[] = [
                $name,
                'Descripcion muy random',
                $this->getReference(['Juguetes', 'Artefactos de cocina', 'Celulares'][random_int(0, 2)]),
                new DateTime('now'),
                random_int(20, 100),
                $this->getRandomColorList()
            ];
        }
        return $productos;
    }

    private function getProductosName() : array
    {
        return [
            'Reloj de pared',
            'Manta verde',
            'Pijama',
            'Samsung galaxy',
            'Palo de escoba',
            'Reloj luminoso',
            'Reloj de mano',
            'Pelota',
            'Pelota de futbol',
            'Computadora',
            'Percheros',
            'Palitos',
            'Platos',
            'Canasta',
            'Cables',
            'Zapatos adidas',
            'Cartera',
            'Libreta',
            'Azulejos verdes',
            'Castillo de juguete'
        ];
    }

    private function loadCategorias(ObjectManager $manager)
    {
        foreach ($this->getCategoriaData() as $nombre) {
            $categoria = new Categoria();

            $categoria->setNombre($nombre);

            $manager->persist($categoria);
            $this->addReference($nombre, $categoria);
        }

        $manager->flush();
    }

    private function getCategoriaData(): array
    {
        // [$nombre]
        return [
            'Juguetes',
            'Artefactos de cocina',
            'Celulares',
            'Equipos',
            'Ropa',
            'Accesorios',
            'Joyeria'
        ];
    }

    private $passwordEncoder;
    private $projectDir;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, string $projectDir)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->projectDir = $projectDir;
    }

    private function copyImages()
    {
        $source = $this->projectDir.'/public/images';
        $dest = $this->projectDir.'/public/uploads';
        $imageNames = [];

        $files = scandir($source);
        foreach ($files as $fileName) 
        {
            if($fileName != '..' and $fileName != '.')
            {
                echo 'Mira  loca '.$source.'/'.$fileName;
                copy($source.'/'.$fileName, $dest.'/'.$fileName);
                $imageNames[] = $fileName;
            }
        }
        return $imageNames;
    }

    private function loadImages(ObjectManager $manager)
    {
        //$imagesCollection = $this->getImageNames();
        $imagesCollection = $this->copyImages();
        foreach ($imagesCollection as $name) {
            $image = new Image();
            $image->setFileName($name);
            $manager->persist($image);
            $this->addReference($image->getFileName(), $image);
        } 

        $manager->flush();
    }




    private function getRandomColorList(): array
    {
        $size = random_int(1, 10);
        $list = [];
        $colors = $this->getColorsData();
        $colorsCount = count($colors);

        for ($i=0; $i < $size; $i++) { 
            $newColor = $colors[random_int(0, $colorsCount - 1)];
            if(in_array($newColor, $list)) continue;
            $list[] = $newColor;
        }

        return $list;
    }



    private function getColorsData(): array
    {
        return [
            'royalblue',
            'saddlebrown',
            'aqua',
            'white',
            'yellow',
            'greenyellow',
            'orangered',
            'olive',
            'orange',
            'orchid',
            'magenta',
            'maroon',
            'mediumblue',
            'mediumorchid',
            'mediumseagreen',
            'mediumvioletred',
            'lightblue',
            'lightcoral',
            'lightcyan',
            'lightpink',
            'darkcyan',
            'darkgoldenrod',
            'darkgreen',
            'darkslateblue',
        ];
    }

    private function loadUsers(ObjectManager $manager)
    {
        $dataCollection = $this->getNameEmailRoleAdress();
        $fotoNames = $this->getImageNames();

        for ($i=0; $i < count($dataCollection); $i++) { 
            [$nombre, $email, $roles, $direccion] = $dataCollection[$i];

            $user = new Usuario();
            $user->setNombre($nombre);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'kitten'));
            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setActivo(true);
            $user->setDireccion($direccion);
            $user->setFoto($this->getReference($fotoNames[$i]));

            $manager->persist($user);
            $this->addReference($email, $user);
        }

        $manager->flush();
    }

    private function getNameEmailRoleAdress() : array
    {
        return [
            ['Jane Doe', 'jane_admin@symfony.com', ['ROLE_ADMIN'],  'Calle 5ta'],
            ['Tom Doe',  'tom_admin@symfony.com', ['ROLE_ADMIN'],  'Calle 1ra'],
            ['John Doe',  'john_user@symfony.com', ['ROLE_USER'],  'Calle 2da'],
            ['Amalia Ruiz',  'amalia@gmail.com', ['ROLE_USER'],  'Calle 5ta'],
            ['Conrado Perez',  'conrado@symfony.com', ['ROLE_USER'],  'Calle 1ra'],
            ['July Edison',  'july@symfony.com', ['ROLE_USER'],  'Calle 2da']
        ];
    }

    private function loadOrdenes(ObjectManager $manager)
    {
        foreach ($this->getOrdenData() as $i => [$usuario, $producto, $fecha, $cantidad, $estado, $fecha_pago]) {
            $orden = new Orden();
            $orden->setUsuario($usuario);
            $orden->setProducto($producto);
            $orden->setCreated($fecha);
            $orden->setCantidad($cantidad);
            $orden->setEstado($estado);
            $orden->setFechaPago($fecha_pago);

            $manager->persist($orden);
        }
        $manager->flush();
    }

    private function getOrdenData(): array
    {
        // [$usuario, $producto, $fecha, $cantidad, $estado, $fecha_pago]
        $random_user = function(){
            return $this->getReference([
                'amalia@gmail.com', 
                'july@symfony.com',
                'john_user@symfony.com'][random_int(0, 2)]);
        };

        $random_product = function(){
            $names = $this->getProductosName();
            return $this->getReference($names[random_int(0, count($names) -1)]);
        };

        return [
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'pagado', new DateTime('now + 14 days')],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'pagado', new DateTime('now + 3 days')],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'pagado', new DateTime('now + 3 days')],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'pagado', new DateTime('now + 6 days')],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'pagado', new DateTime('now + 14 days')],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'pagado', new DateTime('now + 11 days')],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'cancelado', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'cancelado', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'cancelado', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'cancelado', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'cancelado', null],
            [$random_user(), $random_product(), new DateTime('now'), random_int(1, 50), 'sin pagar', null],
        ];
    }


}
