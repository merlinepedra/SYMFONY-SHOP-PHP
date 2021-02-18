<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Producto;
use App\Entity\Usuario;
use App\Entity\Orden;
use App\Entity\Categoria;
use DateTime;
use Symfony\Component\Validator\Constraints\Length;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadProductos($manager);
        $this->loadUsers($manager);
        $this->loadOrdenes($manager);
    }

    private function loadProductos(ObjectManager $manager)
    {
        $this->loadCategorias($manager);

        foreach ($this->getProductoData() as [$nombre, $descripcion, $categoria, $fecha_creacion, $precio_unidad]) {
            $producto = new Producto();
            $producto->setNombre($nombre);
            $producto->setDescripcion($descripcion);
            $producto->setCategoria($categoria);
            $producto->setFechaCreacion($fecha_creacion);
            $producto->setPrecioUnidad($precio_unidad);

            $manager->persist($producto);
            $this->addReference($nombre, $producto);
        }

        $manager->flush();
    }

    private function getProductoData() : array
    {
        //[$nombre, $descripcion, $categoria, $fecha_creacion, $precio_unidad]
        $productos = [];
        foreach ($this->getProductosName() as $i => $name) {
            $productos[] = [
                $name,
                'Descripcion muy random',
                $this->getReference(['Juguetes', 'Artefactos de cocina', 'Celulares'][random_int(0, 2)]),
                new DateTime('now'),
                random_int(20, 100)
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

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$nombre, $password, $email, $roles, $activo, $direccion, $fecha_join, $ultima_fecha_acceso]) {
            $user = new Usuario();
            $user->setNombre($nombre);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setActivo($activo);
            $user->setDireccion($direccion);
            $user->setFechaJoin($fecha_join);
            $user->setUltimaFechaAcceso($ultima_fecha_acceso);

            $manager->persist($user);
            $this->addReference($email, $user);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        $fecha = new \DateTime('now + 5 seconds');
        // [$nombre, $password, $email, $roles, $activo, $direccion, $fecha_join, $ultima_fecha_acceso]
        return [
            ['Jane Doe', 'kitten', 'jane_admin@symfony.com', ['ROLE_ADMIN'], true, 'Calle 5ta', $fecha, $fecha],
            ['Tom Doe', 'kitten', 'tom_admin@symfony.com', ['ROLE_ADMIN'], true, 'Calle 1ra', $fecha, $fecha],
            ['John Doe', 'kitten', 'john_user@symfony.com', ['ROLE_USER'], true, 'Calle 2da', $fecha, $fecha],
            ['Amalia Ruiz', 'kitten', 'amalia@gmail.com', ['ROLE_USER'], true, 'Calle 5ta', $fecha, $fecha],
            ['Conrado Perez', 'kitten', 'conrado@symfony.com', ['ROLE_USER'], true, 'Calle 1ra', $fecha, $fecha],
            ['July Edison', 'kitten', 'july@symfony.com', ['ROLE_USER'], true, 'Calle 2da', $fecha, $fecha]
        ];
    }

    private function loadOrdenes(ObjectManager $manager)
    {
        foreach ($this->getOrdenData() as $i => [$usuario, $producto, $fecha, $cantidad, $estado, $fecha_pago]) {
            $orden = new Orden();
            $orden->setUsuario($usuario);
            $orden->setProducto($producto);
            $orden->setFechaOrden($fecha);
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
