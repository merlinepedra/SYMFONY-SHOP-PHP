<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Sonata\AdminBundle\Form\Type\ModelType;


use Sonata\DoctrineORMAdminBundle\Filter\DateRangeFilter;
use Sonata\DoctrineORMAdminBundle\Filter\CountFilter;

use App\Entity\Usuario;
use App\Entity\Producto;



final class OrdenAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('usuario', ModelType::class, array(
            'class' => Usuario::class,
            'property' => 'nombre',
            'required' => true));
        $formMapper->add('producto', ModelType::class, array(
            'class' => Producto::class,
            'property' => 'nombre',
            'required' => true));
        $formMapper->add('fecha_orden', DateTimeType::class, array(
            'required' => true,
            'label' => 'Ordenado'
        ));
        $formMapper->add('cantidad', NumberType::class, array('required' => true));
        $formMapper->add('fecha_pago', DateTimeType::class, array(
            'required' => false,
            'label' => 'Pagado'
        ));
        $formMapper->add('estado', ChoiceType::class, array(
            'required' => true,
            'multiple' => false,
            'expanded' => false,
            'choices' => [
                'Pagado' => 'pagado',
                'Cancelado' => 'cancelado',
                'Sin pagar' => 'sin pagar',
            ]
        ));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('usuario.nombre', null, array('label' => 'Usuario'));
        $datagridMapper->add('producto.nombre', null, array('label' => 'Producto'));
        $datagridMapper->add('fecha_orden', DateRangeFilter::class, array('label' => 'Fecha'));
        $datagridMapper->add('cantidad', CountFilter::class);
        $datagridMapper->add('fecha_pago', DateRangeFilter::class, array('label' => 'Pago'));
        $datagridMapper->add('estado');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('usuario.nombre', null, array('label' => 'Usuario'));
        $listMapper->addIdentifier('producto.nombre', null, array('label' => 'Producto'));
        $listMapper->addIdentifier('fecha_orden', null, array('label' => 'Fecha'));
        $listMapper->addIdentifier('cantidad');
        $listMapper->addIdentifier('fecha_pago', null, array('label' => 'Pago'));
        $listMapper->addIdentifier('estado');
    }
}