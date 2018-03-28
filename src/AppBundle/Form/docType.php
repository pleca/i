<?php
/**
 * Created by PhpStorm.
 * User: gborycki
 * Date: 2017-09-04
 * Time: 13:53
 */

namespace AppBundle\Form;

use AppBundle\Entity\IntraDocuments;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\IntraDocumentCategory;

class docType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('documentDesc', TextareaType::class, array('label' => 'Opis', 'required' => false, 'attr' => array('class' => 'tinymce')))
            ->add('DocumentFile', FileType::class, array('label' => 'Plik'))
//            ->add('category', EntityType::class, array( 'class' => IntraDocumentCategory::class,
//                'choice_label' => 'name',
//                'label' => 'Kategoria',
//                ))
            ->add('category', ChoiceType::class,
                array("label" => "Kategoria",
                    "choices" => $this->fillCategories($options['entityManager']),
                    'mapped' => false,
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Dodaj',
                'attr' => array('class' => 'btn btn-primary')
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => IntraDocuments::class,
            'entityManager' => null,
        ));
    }

    private function fillCategories($em)
    {
        $sql = " 
   SELECT `id` as `categoryId`,`name`,`parent_id` as `parentId`, ( SELECT LPAD(`intra_document_category`.id, 5, '0') FROM `intra_document_category` parent WHERE parent.id = `intra_document_category`.id AND parent.parent_id = 0 UNION SELECT CONCAT(LPAD(parent.id, 5, '0'), '.', LPAD(child.id, 5, '0')) FROM `intra_document_category` parent INNER JOIN `intra_document_category` child ON (parent.id = child.parent_id) WHERE child.id = `intra_document_category`.id AND parent.parent_id = 0 ) AS level2 FROM `intra_document_category` order by level2
    ";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();

        foreach ($data as $key => $value) {
            if ($value['parentId'] < 1) {
                $data[$key]['name'] = strtoupper($value['name']);
            } else {
//                $data[$key]['name'] = "&nbsp;&nbsp;&nbsp;&nbsp;" . strtolower($value['name']);
                $data[$key]['name'] = " - " . strtolower($value['name']);
            }
        }

        $categories = array();
        foreach ($data as $c) {
            $categories[$c['name']] = $c['categoryId'];
        }

        return $categories;
    }
}