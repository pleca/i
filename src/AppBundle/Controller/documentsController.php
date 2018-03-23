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
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->from('AppBundle:IntraDocumentCategory', 'c')
            ->select("c.id, c.parentId, c.name")
            ->getQuery();
        $data = $qb->getArrayResult();

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
        $form = $this->createForm(docType::class, $docf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $docf->getDocumentFile();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $this->getParameter('docs_directory'),
                $fileName
            );

            $currentTime = new \DateTime(date("Y-m-d H:i:s"));
            $userId = $this->getUser()->getUserId();

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
     */
    public function updateAction(Request $request)
    {
        $data = $request->request->all();

        if (!$data) {
            return new Response('{ "errors": ["Brak danych", "Wprowadź dane"] }', 200);
        }

        if (!$data["documentId"]) {
            return new Response('{ "errors": ["Brak danych", "Wprowadź dane"] }', 200);
        }

        $em = $this->getDoctrine()->getManager();
        $document = $em->getRepository('AppBundle:IntraDocuments')->find($data["documentId"]);

        $document->setDocumentDesc($data["documentDesc"]);

        if ($_FILES['files']) {
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
        $em->persist($document);
        $em->flush();

        return $this->redirect($this->generateUrl('Dokumenty'));
    }

    /**
     * @Route("/category", name="document_category")
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