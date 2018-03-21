<?php

namespace AppBundle\Form;

use AppBundle\Entity\IntraDocumentCategory;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentCategoryUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nazwa'))
            ->add('save', SubmitType::class, array('label' => 'Aktualizuj',
                'attr' => array('class' => '.btn-success')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => IntraDocumentCategory::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_document_category_update_type';
    }
}
