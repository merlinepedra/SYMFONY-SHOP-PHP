<?php
namespace App\Admin;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


use Sonata\DoctrineORMAdminBundle\Filter\DateTimeFilter;
use Sonata\DoctrineORMAdminBundle\Filter\StringFilter;


final class ImagenAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->add('file', FileType::class, array(
            'required' => false,
            'mapped' => false,
            'label' => 'Imagen',
            'constraints' => [new Image([
                'mimeTypesMessage' => 'Por favor seleccione un archivo tipo imagen válido.'])],
            ));
        /*
        ->add('updated', DateTimeType::class, array(
            'required' => true,
            'label' => 'Actualizado'
        ))
        ->add('fileName', null, array(
            'required' => true,
            'label' => 'Nombre'
        ));
        */
    }

    public function prePersist($image)
    {
        $this->manageFileUpload($image);
    }

    public function preUpdate($image)
    {
        $this->manageFileUpload($image);
    }

    private function manageFileUpload($image)
    {
        $myfile = fopen("newfile2.txt", "w") or die("Unable to open file!");
        fwrite($myfile, new \DateTime());
        fwrite($myfile, PHP_EOL);
        fwrite($myfile, 'Vamos a ver el archivo');
        fwrite($myfile, PHP_EOL);
        fwrite($myfile, PHP_EOL);
        fwrite($myfile, PHP_EOL);
        
        if ($image->getFile()) {
            $image->setFileName($image->getFile()->getClientOriginalName());
            $image->refreshUpdated();
        }
        fclose($myfile);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('fileName', null, ['label' => 'Nombre del archivo']);
        $listMapper->addIdentifier('update', DateTimeType::class, ['label' => 'Actualizado']);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('fileName', StringFilter::class, ['label' => 'Nombre del archivo']);
        $datagridMapper->add('update', DateTimeFilter::class, ['label' => 'Actualizado']);
    }
}