<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\IntraEvents;
use AppBundle\Form\addNews;
use AppBundle\Form\editNews;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="Strona Główna")
     */
	 /*
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('intranet/news.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    
	/////////////
	}*/
	
	/**
     * @Route("/", name="Strona Główna")
     */
    public function newsAction(Request $request)
    {
        $newsForm = new IntraEvents();
        $form = $this->createForm(addNews::class, $newsForm);
        $news = $this->getDoctrine()
            ->getRepository('AppBundle:IntraEvents')
            ->findBy(array('newsType' => 1));

        $user = $this->get('security.token_storage')->getToken()->getUsername();
        if ($user != "anon.") {

            #$file_s = $this->getDoctrine()->getRepository('AppBundle:IntraUser')->findOneBy(array('userName' => $user));

            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                "SELECT c.userType FROM AppBundle:IntraUser c WHERE c.userName ='" . $user . "'"
            );
            $userType = $query->getArrayResult();
            $userType = $userType[0]['userType'];
            $userId = $this->getUser()->getuserId();

            $newsForm->setNewsCreatorId($userId);
            $newsForm->setNewsUserId($userId);
            $newsForm->setNewsType('1');
            $newsForm->setNewsStatus('1');
            $newsForm->setNewsDateAdd(new \DateTime('now', (new \DateTimeZone('Europe/Warsaw'))));
            $newsForm->setNewsDateMod(new \DateTime('now', (new \DateTimeZone('Europe/Warsaw'))));
            $newsForm->setNewsStart(new \DateTime('now', (new \DateTimeZone('Europe/Warsaw'))));
            $newsForm->setNewsEnd(new \DateTime('now', (new \DateTimeZone('Europe/Warsaw'))));
            $form = $this->createForm(addNews::class, $newsForm);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $file = $newsForm->getNewsImage();
                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored

                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );

                // Update the 'brochure' property to store the PDF file name
                // instead of its contents
                $newsForm->setNewsImage($fileName);
                $em = $this->getDoctrine()->getManager();
                $em->persist($newsForm);
                $em->flush();
                $this->addFlash("success", "Aktualność została dodana");
                return $this->redirect($this->generateUrl('Aktualności'));

            }

            return $this->render('intranet/index.html.twig', array(
                'news' => $news,
                'userType' => $userType,
                'form' => $form->createView()
            ));
        }
        else {
            return $this->render('intranet/index.html.twig', array(
                'news' => $news,
                'userType' => "ROLE_USER",
                'form' => $form->createView()
            ));
        }
    }
    /**
     * @Route("/news/{newsTitle}")
     */
    public function singleNewsAction($newsTitle)
    {


        $ct = $this->getDoctrine()
            ->getRepository('AppBundle:IntraEvents')
            ->findBy(array('newsTitle' => $newsTitle));
        /*
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c FROM AppBundle:IntraEvents c WHERE c.newsTitle = '". $newsTitle."'"
        );
        $ct = $query->getArrayResult();
        */
        #print_r($ct);
        return $this->render('intranet/singleNews.html.twig', array(
            'news' => $ct[0]
        ));
    }

    /**
     * @Route("/news/edit/{newsTitle}")
     */
    public function editNewsAction($newsTitle, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c FROM AppBundle:IntraEvents c WHERE c.newsTitle = '". $newsTitle."'"
        );
        $ct = $query->getArrayResult();
        $ct = $ct[0];
        $newsForm = new IntraEvents();
        $form = $this->createForm(editNews::class, $newsForm);
        $form->get('newsTitle')->setData($ct['newsTitle']);
        $form->get('newsShortText')->setData($ct['newsShortText']);
        $form->get('newsText')->setData($ct['newsText']);
        $form->handleRequest($request);
        $post = $em->getRepository('AppBundle:IntraEvents')->find($ct['newsId']);
        /** @var $post IntraEvents */
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setNewsTitle($newsForm->getNewsTitle());
            $post->setNewsShortText($newsForm->getNewsShortText());
            $post->setNewsText($newsForm->getNewsText());
            $file = $newsForm->getNewsImage();
            if(isset($file)) {

                $image = $ct['newsImage'];
                $file_path = $this->getParameter('image_directory').'/'.$image;
                if(file_exists($file_path)) unlink($file_path);

                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );

                $newsForm->setNewsImage($fileName);
                $post->setNewsImage($fileName);
            }
            $post->setNewsDateAdd($ct['newsDateAdd']);
            $post->setNewsDateMod(new \DateTime('now', (new \DateTimeZone('Europe/Warsaw'))));
            $post->setNewsStart(new \DateTime('now', (new \DateTimeZone('Europe/Warsaw'))));
            $post->setNewsEnd(new \DateTime('now', (new \DateTimeZone('Europe/Warsaw'))));
            $post->setNewsCreatorId($ct['newsCreatorId']);
            $post->setNewsUserId($ct['newsUserId']);
            $post->setNewsType($ct['newsType']);
            $post->setNewsStatus($ct['newsStatus']);
            $post->getNewsTitle();
            $post->getNewsShortText();
            $post->getNewsText();

            $em->flush();
            $this->addFlash("success", "Aktualność została uaktualniona");
            return $this->redirect($this->generateUrl('Aktualności'));

        }

        return $this->render('intranet/editNews.html.twig', array(
            'news' => $ct,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/deleteNews/{newsId}", name="deleteNews")
     */
    public function deleteAction($newsId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $news = $em->getRepository('AppBundle:IntraEvents')->find($newsId);

        $em2 = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c.newsImage FROM AppBundle:IntraEvents c WHERE c.newsId ='".$newsId."'"
        );

        $newsImage = $query->getArrayResult();
        $image = $newsImage[0]['newsImage'];
        $file_path = $this->getParameter('image_directory').'/'.$image;
        if(file_exists($file_path)) unlink($file_path);
        if (!$news) {
            $this->addFlash("danger", "Wystąpił błąd");
            return $this->redirectToRoute('Aktualności');
        }

        $em->remove($news);
        $em->flush();
        $this->addFlash("success", "Aktualność została pomyślnie usunięta");
        return $this->redirectToRoute('Aktualności');
    }
	
	
	
	
}
