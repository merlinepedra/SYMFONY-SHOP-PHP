<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints\Image;


use Sonata\DoctrineORMAdminBundle\Filter\DateRangeFilter;
use Sonata\DoctrineORMAdminBundle\Filter\StringFilter;
use Sonata\DoctrineORMAdminBundle\Filter\BooleanFilter;



final class UsuarioAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('nombre', TextType::class, array('required' => true));
        $formMapper->add('email', EmailType::class, array(
            'required' => true,
            'label' => 'Correo'
        ));
        $formMapper->add('roles', ChoiceType::class, [
            'multiple' => true,
            'expanded' => true,
            'choices' => [
                'Administrador' => 'ROLE_ADMIN',
                'Usuario' => 'ROLE_USER' 
            ],
        ]);
        $formMapper->add('password', PasswordType::class, array(
            'required' => true,
            'label' => 'Contraseña'
        ));
        $formMapper->add('activo', ChoiceType::class, array(
            'multiple' => false,
            'expanded' => true,
            'choices' => [
                'Sí' => true,
                'No' => false 
            ],
            'required' => true));
        $formMapper->add('direccion', TextareaType::class, array(
            'required' => true,
            'label' => 'Dirección'
        ));
        $formMapper->add('foto', FileType::class, array(
            'required' => false,
            'mapped' => false,
            'label' => 'Foto de perfil',
            'constraints' => [new Image([
                'mimeTypesMessage' => 'Por favor seleccione un archivo tipo imagen válido.'])],
        ));
        $formMapper->add('fecha_join', DateTimeType::class, array(
            'required' => true,
            'label' => 'Unión a la plataforma'
        ));
        $formMapper->add('ultima_fecha_acceso', DateTimeType::class, array(
            'required' => true,
            'label' => 'Último acceso'
        ));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('nombre');
        $datagridMapper->add('email', StringFilter::class, ['label'=>'Correo']);
        $datagridMapper->add('direccion', StringFilter::class, ['label' => 'Dirección']);
        $datagridMapper->add('activo', BooleanFilter::class);
        $datagridMapper->add('fecha_join', DateRangeFilter::class, array(
            'label' => 'Unión a la plataforma'));
        $datagridMapper->add('ultima_fecha_acceso', DateRangeFilter::class, array(
            'label' => 'Último acceso'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('nombre');
        $listMapper->addIdentifier('email', null, ['label' => 'Correo']);
        $listMapper->addIdentifier('activo');
        $listMapper->addIdentifier('roles');
        $listMapper->addIdentifier('direccion', null, ['label' => 'Dirección']);
        $listMapper->addIdentifier('foto');
        $listMapper->addIdentifier('fecha_join', null, array('label' => 'Unión'));
        $listMapper->addIdentifier('ultima_fecha_acceso', null, array('label' => 'Último acceso'));
    }
}