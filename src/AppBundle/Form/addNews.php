<?php

namespace AppBundle\Form;

use AppBundle\Entity\IntraEvents;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Tests\Fixtures\FooBarHTMLType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class addNews extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options); // TODO: Change the autogenerated stub
        $builder->add('newsTitle', TextType::class, array('label' => 'Tytuł'))
            ->add('newsShortText', TextType::class, array('label' => 'Skrócony opis aktualności'))
            ->add('newsText',TextareaType::class, array('label' => 'Opis Aktualności'))
            ->add('newsImage', FileType::class, array('label' => 'Dodaj zdjęcie'))
            ->add('save', SubmitType::class, array('label' => 'Dodaj'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        //parent::configureOptions($resolver); // TODO: Change the autogenerated stub
        $resolver->setDefaults(array(
            'data_class' => IntraEvents::class,
        ));
    }

    public function getName()
    {
        return 'app_bundle_intra_events';
    }
}