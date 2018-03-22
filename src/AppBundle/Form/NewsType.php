<?php

namespace AppBundle\Form;

use AppBundle\Entity\IntraEvents;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Tests\Fixtures\FooBarHTMLType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

//todo: zlikwidować dwa form buildery addNews.php i editNews.php (bo powtarzają się) i zastosować ten jeden
class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options); // TODO: Change the autogenerated stub
        $builder->add('newsTitle', TextType::class, array('label' => 'Tytuł'))
            ->add('newsShortText', TextType::class, array('label' => 'Skrócony opis aktualności'))
            ->add('newsText',TextareaType::class, array('label' => 'Opis Aktualności'))
            ->add('newsDatePublication', DateTimeType::class, array('label' => 'Data publikacji'))
            ->add('newsImage', FileType::class, array('label' => 'Dodaj zdjęcie','required' => false))
            ->add('save', SubmitType::class, array('label' => 'Zapisz'));
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