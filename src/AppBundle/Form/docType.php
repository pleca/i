<?php
/**
 * Created by PhpStorm.
 * User: gborycki
 * Date: 2017-09-04
 * Time: 13:53
 */

namespace AppBundle\Form;

use AppBundle\Entity\IntraDocuments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class docType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ...
            ->add('documentDesc', TextareaType::class, array('label' => 'Opis', 'required' => false, 'attr' => array('class' => 'tinymce')))
            ->add('DocumentFile', FileType::class, array('label' => 'Plik'))
            ->add('submit', SubmitType::class, array('label' => 'Dodaj'))
            // ...
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => IntraDocuments::class,
        ));
    }


}