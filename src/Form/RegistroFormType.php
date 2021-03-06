<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;


class RegistroFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', null, ['label'=>'Nombre y apellidos'])
            ->add('email', EmailType::class, ['label'=>'Correo'])
            //->add('roles')
            ->add('password', PasswordType::class, ['label'=>'Contraseña'])
            //->add('activo')
            ->add('direccion', TextareaType::class, ['label'=>'Dirección particular'])  
            ->add('foto', FileType::class, [
                'label'=> 'Foto de perfil',
                'mapped' => false,
                'required' => false,
                'constraints'=> [
                    new Image([
                        'mimeTypesMessage'=>'Este archivo no es una imagen válida.'
                    ])
                ]])
            //->add('fecha_join')
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
            'allow_extra_fields' => true
        ]);
    }
}