<?php
/**
 * Created by PhpStorm.
 * User: gborycki
 * Date: 8/24/2017
 * Time: 8:02 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\IntraDocuments;
use AppBundle\Form\docType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class documentsController extends Controller
{
    private function get_json_array()
    {
        $ems = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->from('AppBundle:IntraDocuments', 'd')
            ->select("d.documentId, d.documentFile, d.documentFileTitle, d.documentDateAdd, d.documentDateMod, d.documentDesc, u.userName, u.userLastname")
            ->leftJoin("AppBundle:IntraUser", "u", "WITH", "d.documentCreatorId=u.userId")
            ->addOrderBy("d.documentId", "DESC")
            ->setMaxResults(10000)
            ->getQuery();
        $data = $ems->getArrayResult();

        foreach ($data as $key => $row) {
            $arr[] =
                array(
                    "documentId" => $row["documentId"],
                    "documentFile" => $row["documentFile"],
                    "documentFileTitle" => $row["documentFileTitle"],
                    "documentDateAdd" => $row["documentDateAdd"]->format('Y-m-d H:i:s'),
                    "documentDateMod" => $row["documentDateMod"]->format('Y-m-d H:i:s'),
                    "documentDesc" => $row["documentDesc"],
                    "user" => $row["userName"] . ' ' . $row["userLastname"]

                );
        }
        if (isset($arr)) {
            return $arr;
        }
    }

    /**
     * @Route("/documents", name="Dokumenty")
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

            return $this->redirect($this->generateUrl('Dokumenty'));
        }

        $arr = $this->get_json_array();
        return $this->render('intranet/docs.html.twig', array('docs_json' => json_encode($arr), 'form' => $form->createView()));
    }

    /**
     * @Route("/documents/get_docs", name="getjsondocs")
     */
    public function json_page()
    {
        $arr = $this->get_json_array();
        return new Response(json_encode($arr), 200);
    }

    /**
     * @Route("/documents/delete", name="deletedoc")
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

        #$testfile = fopen($this->getParameter('docs_directory')."newfile_update.txt", "w") or die("Unable to open file!");
        #fwrite($testfile, print_r($request , TRUE));
        #fclose($testfile);
        return new Response(json_encode($req, true), 200);
    }

    /**
     * @Route("/documents/update", name="updatedoc")
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
}