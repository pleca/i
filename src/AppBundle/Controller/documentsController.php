<?php
/**
 * Created by PhpStorm.
 * User: gborycki
 * Date: 8/24/2017
 * Time: 8:02 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\IntraDocumentCategory;
use AppBundle\Entity\IntraDocuments;
use AppBundle\Form\docType;
use AppBundle\Form\DocumentCategoryType;
use AppBundle\Form\DocumentCategoryUpdateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/documents")
 */
class documentsController extends Controller
{
    /**
     * @Route("/test", name="test")
     */
    public function testAction()
    {

        return $this->render('intranet/test.html.twig', []);
    }

    /**
     * @Route("/get_doc_categories", name="get_json_categories")
     */
    public function getJsonCategories()
    {

        $em = $this->getDoctrine()->getManager();

        /**
         * zapytanie zwraca grupy i ich pogrupy kolejno, jedna grupa i jej dzieci i kolejna grupa i jej dzieci
         */
        $sql = " 
   SELECT `id` as `categoryId`,`name`,`parent_id` as `parentId`, ( SELECT LPAD(`intra_document_category`.id, 5, '0') FROM `intra_document_category` parent WHERE parent.id = `intra_document_category`.id AND parent.parent_id = 0 UNION SELECT CONCAT(LPAD(parent.id, 5, '0'), '.', LPAD(child.id, 5, '0')) FROM `intra_document_category` parent INNER JOIN `intra_document_category` child ON (parent.id = child.parent_id) WHERE child.id = `intra_document_category`.id AND parent.parent_id = 0 ) AS level2 FROM `intra_document_category` order by level2
    ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();

        foreach ($data as $key => $value) {
            if ($value['parentId'] < 1) {
                $data[$key]['name'] = strtoupper($value['name']);
            } else {
                $data[$key]['name'] = '   ' . strtolower($value['name']);
            }
        }

        return new Response(json_encode($data), 200);
    }

    private function get_json_array()
    {
        $qb1 = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->from('AppBundle:IntraDocuments', 'd')
            ->select("d.documentId, d.documentFile, d.documentFileTitle, d.documentDateAdd, d.documentDateMod, d.documentDesc, u.userName, u.userLastname, c.id, c.parentId, c.name")
            ->leftJoin("AppBundle:IntraUser", "u", "WITH", "d.documentCreatorId=u.userId")
            ->leftJoin("AppBundle:IntraDocumentCategory", "c", "WITH", "d.documentCategory=c.id")
            ->addOrderBy("d.documentId", "DESC")
            ->setMaxResults(10000)
            ->getQuery();
        $data = $qb1->getArrayResult();

        foreach ($data as $key => $row) {
            if ($row["parentId"] == 0) {
                $category = $row["name"];
                $subcategory = null;
            } else {
                $qb2 = $this->getDoctrine()->getManager()->createQueryBuilder()
                    ->select('c.name')
                    ->from('AppBundle:IntraDocumentCategory', 'c')
                    ->where('c.id = :id')
                    ->setParameter('id', $row["parentId"])
                    ->getQuery();
                $category = $qb2->getSingleScalarResult();
                $subcategory = $row["name"];
            };
            $arr[] =
                array(
                    "documentId" => $row["documentId"],
                    "documentFile" => $row["documentFile"],
                    "documentFileTitle" => $row["documentFileTitle"],
                    "documentDateAdd" => $row["documentDateAdd"]->format('Y-m-d H:i:s'),
                    "documentDateMod" => $row["documentDateMod"]->format('Y-m-d H:i:s'),
                    "documentDesc" => $row["documentDesc"],
                    "user" => $row["userName"] . ' ' . $row["userLastname"],
                    "category" => $category,
                    "subcategory" => $subcategory,
                    "categoryId" => $row["id"],

                );
        }
        if (isset($arr)) {
            return $arr;
        }
    }

    /**
     * @Route("/", name="Dokumenty")
     */
    public function filesAction(Request $request)
    {
        $docf = new IntraDocuments();
        $form = $this->createForm(
            docType::class,
            $docf,
            ['entityManager' => $this->getDoctrine()->getManager(),]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $docf->getDocumentFile();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $this->getParameter('docs_directory'),
                $fileName
            );

            $formData=$request->get('doc');
            $categoryId = $formData['category'];
            $em = $this->getDoctrine()->getManager();
            $category = $em->getRepository('AppBundle:IntraDocumentCategory')->find($categoryId);

            $currentTime = new \DateTime(date("Y-m-d H:i:s"));
            $userId = $this->getUser()->getUserId();

            $docf->setCategory($category);
            $docf->setDocumentFile($fileName);
            $docf->setdocumentFileTitle($file->getClientOriginalName());
            $docf->setdocumentDateAdd($currentTime);
            $docf->setdocumentDateMod($currentTime);
            $docf->setDocumentType($file->getClientMimeType());
            $docf->setDocumentCreatorId($userId);
            $docf->setDocumentUserId($userId);
            $em = $this->getDoctrine()->getManager();
            $em->persist($docf);
            $em->flush();

            $this->addFlash("success", "Dokument został dodany");

            return $this->redirect($this->generateUrl('Dokumenty'));
        }

        $arr = $this->get_json_array();
        return $this->render('intranet/docs.html.twig', array('docs_json' => json_encode($arr), 'form' => $form->createView()));
    }

    /**
     * @Route("/get_docs", name="getjsondocs")
     */
    public function json_page()
    {
        $arr = $this->get_json_array();
        return new Response(json_encode($arr), 200);
    }

    /**
     * @Route("/delete", name="deletedoc")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $filePath = $this->getParameter('docs_directory');
        $req = $request->request->all();
        $req = $req["models"];
        $out = "";

        foreach ($req as $key => $row) {

            if ($row["documentId"] > 0) {
                $out = " DELETE FROM AppBundle:IntraDocuments c WHERE c.documentId = " . $row["documentId"];

                if (isset($out)) {
                    $em = $this->getDoctrine()->getManager();
                    $em = $em->createQuery($out);
                    $numDeleted = $em->execute();
                }

                if (file_exists($filePath . $row["documentFile"])) {
                    unlink($filePath . $row["documentFile"]);
                }
            }
        }

        return new Response(json_encode($req, true), 200);
    }

    /**
     * @Route("/update", name="updatedoc")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateAction(Request $request)
    {
        $data = $request->request->all();

        if (!$data) {
            return new Response('{ "errors": ["Brak danych", "Wprowadź dane"] }', 200);
        }
//        $data = $data['models'][0];
        if (!$data["documentId"]) {
            return new Response('{ "errors": ["Brak danych", "Wprowadź dane"] }', 200);
        }

        $em = $this->getDoctrine()->getManager();
        $document = $em->getRepository('AppBundle:IntraDocuments')->find($data["documentId"]);
        $category = $em->getRepository('AppBundle:IntraDocumentCategory')->findOneBy(['id' => $data["categoryId"]]);

        $document->setDocumentDesc($data["documentDesc"]);

        if (!empty($_FILES['files']) and ($_FILES['files']['size'] > 0)) {
            $file = new UploadedFile(
                $_FILES['files']['tmp_name'],
                $_FILES['files']['name'],
                $_FILES['files']['type'],
                $_FILES['files']['size'],
                $_FILES['files']['error']
            );
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('docs_directory'), $fileName);
            $document->setDocumentFile($fileName);
            $document->setDocumentFileTitle($file->getClientOriginalName());
            $document->setDocumentType($file->getClientMimeType());
        }
        $userId = $this->getUser()->getUserId();
        $document->setDocumentDateMod(new \DateTime(date("Y-m-d H:i:s")));
        $document->setDocumentUserId($userId);
        $document->setDocumentCreatorId($userId);
        $document->setCategory($category);
        $em->persist($document);
        $em->flush();

        return $this->redirect($this->generateUrl('Dokumenty'));
    }

    /**
     * @Route("/category", name="document_category")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newCategoryAction(Request $request)
    {
        $documentCategory = new IntraDocumentCategory();
        $form = $this->createForm(DocumentCategoryType::class, $documentCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mainCheckbox = $form->get('mainCheckbox')->getData();
            $parentId = $documentCategory->getParentId();
            if (!$parentId and $mainCheckbox) {
                $documentCategory->setParentId(0);
            } else {
                $documentCategory->setParentId($documentCategory->getParentId());
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($documentCategory);
            $em->flush();
            $this->addFlash("success", "Kategoria została dodana");

            return $this->redirect($this->generateUrl('document_category'));
        }

        $categories = $this->getDoctrine()
            ->getRepository(IntraDocumentCategory::class)
            ->findAll();

        return $this->render('intranet/Document/newCategory.html.twig', array('categories' => $categories,
                'form' => $form->createView())
        );
    }

    /**
     * @Route("/category/{id}/update", name="document_category_update")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateCategoryAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:IntraDocumentCategory')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Nie można znaleźć kategori.');
        }

        $form = $this->createForm(DocumentCategoryUpdateType::class, $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            $this->addFlash("success", "Kategoria została edytowana");
            return $this->redirect($this->generateUrl('document_category'));
        }

        return $this->render('intranet/Document/editCategory.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/category/{id}/delete", name="document_category_delete")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteCategoryAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:IntraDocumentCategory')->findOneBy(array('id' => $id));
        $subcategories = $em->getRepository('AppBundle:IntraDocumentCategory')->findBy(array('parentId' => $id));

        if (!$category) {
            throw $this->createNotFoundException('Nie można znaleźć kategorii.');
        }

        if ($subcategories) {
            foreach ($subcategories as $subcategory) {
                $em->remove($subcategory);
            }
        }

        $em->remove($category);
        $em->flush();

        $this->addFlash("success", "Kategoria została usunięta");

        return $this->redirect($this->generateUrl(('document_category')));
    }

}