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
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints\Image;


use Sonata\DoctrineORMAdminBundle\Filter\DateRangeFilter;
use Sonata\DoctrineORMAdminBundle\Filter\StringFilter;
use Sonata\DoctrineORMAdminBundle\Filter\BooleanFilter;


use Sonata\AdminBundle\Form\Type\AdminType;


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
            'required' => true           
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
        $formMapper
        /*
        ->add('foto', FileType::class, array(
            'required' => false,
            'mapped' => false,
            'label' => 'Foto de perfil',
            'constraints' => [new Image([
                'mimeTypesMessage' => 'Por favor seleccione un archivo tipo imagen válido.'])],
        ));
        */
        ->add('foto', AdminType::class, [
            'delete' => false,
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('nombre');
        $datagridMapper->add('email', StringFilter::class, ['label'=>'Correo']);
        $datagridMapper->add('direccion', StringFilter::class, ['label' => 'Dirección']);
        $datagridMapper->add('activo', BooleanFilter::class);
        $datagridMapper->add('fecha_join', DateRangeFilter::class, array(
            'label' => 'Unión a la plataforma'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('nombre');
        $listMapper->addIdentifier('email', null, ['label' => 'Correo']);
        $listMapper->addIdentifier('password', null, ['label' => 'Contraseña']);
        $listMapper->addIdentifier('activo');
        $listMapper->addIdentifier('roles');
        $listMapper->addIdentifier('direccion', null, ['label' => 'Dirección']);
        $listMapper->addIdentifier('foto');
        $listMapper->addIdentifier('fecha_join', null, array('label' => 'Unión'));
    }

    public function prePersist($user) { // $user is an instance of App\Entity\User as specified in services.yaml
        $this->encrypt_password($user);
        $this->manageEmbeddedImageAdmins($user);
    }

    public function preUpdate($user)
    {
        $this->encrypt_password($user);
        $this->manageEmbeddedImageAdmins($user);
    }

    private function encrypt_password($user)
    {
        $plainPassword = $user->getPassword();
        $container = $this->getConfigurationPool()->getContainer();
        $encoder = $container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $plainPassword);
    
        $user->setPassword($encoded);
    }

    private function manageEmbeddedImageAdmins($user)
    {        
        // Cycle through each field
        foreach ($this->getFormFieldDescriptions() as $fieldName => $fieldDescription) 
        {
            // detect embedded Admins that manage Images
            if ($fieldDescription->getType() === 'Sonata\AdminBundle\Form\Type\AdminType' &&
                ($associationMapping = $fieldDescription->getAssociationMapping()) &&
                $associationMapping['targetEntity'] === 'App\Entity\Image') 
            {

                $getter = 'get'.$fieldName;
                $setter = 'set'.$fieldName;


                /** @var Image $image */
                $image = $user->$getter();
                $imageForm = $this->getForm()->get('foto');
                $file = $imageForm['file']->getData();                             

                if ($image && $file) $image->setFile($file);
                else $user->$setter(null);
            }
        }
    }
}