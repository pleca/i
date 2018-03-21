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

class DocumentCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, array(
                'label' => 'Podkategoria', 'label_attr' => array('id' => 'app_bundle_document_label_subcategory')))
            ->add('mainCheckbox', CheckboxType::class, array(
                'label' => 'Kategoria główna',
                "mapped" => false,
                "required" => false,
            ))
            ->add('parentId', EntityType::class, array(
                'label' => 'Kategoria główna',
                'class' => IntraDocumentCategory::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.parentId = :parent')
                        ->setParameter('parent', 0);
                },
                'choice_label' => 'name',
            ))
            ->add('save', SubmitType::class, array('label' => 'Dodaj',
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
        return 'app_bundle_document_category_type';
    }
}
