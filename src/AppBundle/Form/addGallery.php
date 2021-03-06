<?php

namespace AppBundle\Form;

use AppBundle\Entity\IntraGallery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class addGallery extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options); // TODO: Change the autogenerated stub
        $builder->add('galleryImages', FileType::class, array('label' => 'Dodaj zdjęcie'))
            ->add('save', SubmitType::class, array('label' => 'Dodaj'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        //parent::configureOptions($resolver); // TODO: Change the autogenerated stub
        $resolver->setDefaults(array(
            'data_class' => IntraGallery::class,
        ));
    }

    public function getName()
    {
        return 'app_bundle_intra_gallery';
    }
}