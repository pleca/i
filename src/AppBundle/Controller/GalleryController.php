<?php
/**
 * Created by PhpStorm.
 * User: kszyszko
 * Date: 22.08.2017
 * Time: 13:02
 */

namespace AppBundle\Controller;

use AppBundle\Entity\IntraAlbum;
use AppBundle\Entity\IntraGallery;
use AppBundle\Entity\IntraUser;
use AppBundle\Form\addAlbum;
use AppBundle\Form\addGallery;
use AppBundle\Form\editAlbum;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class GalleryController extends Controller

{
    /**
     * @Route("/album", name="Galeria")
     */
    public function albumAction(Request $request)
    {
        $album = $this->getDoctrine()
            ->getRepository('AppBundle:IntraAlbum')
            ->findAll();
        $albumForm = new IntraAlbum();
        $form = $this->createForm(addAlbum::class, $albumForm);

        $user = $this->get('security.token_storage')->getToken()->getUsername();
        if ($user != "anon.") {

            //$file_s = $this->getDoctrine()->getRepository('AppBundle:IntraUser')->findOneBy(array('userName' => $user));

            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                "SELECT c.userType FROM AppBundle:IntraUser c WHERE c.userName ='" . $user . "'"
            );
            $userType = $query->getArrayResult();
            $userType = $userType[0]['userType'];
            $userId = $this->getUser()->getUserId();

            $albumForm->setAlbumCreatorId($userId);
            $albumForm->setAlbumModId($userId);
            $albumForm->setAlbumDateAdd(new \DateTime('now', (new \DateTimeZone('Europe/Warsaw'))));
            $albumForm->setAlbumDateMod(new \DateTime('now', (new \DateTimeZone('Europe/Warsaw'))));

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $file = $albumForm->getAlbumImage();
                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored

                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );

                // Update the 'brochure' property to store the PDF file name
                // instead of its contents
                $albumForm->setAlbumImage($fileName);
                $em = $this->getDoctrine()->getManager();
                $em->persist($albumForm);
                $em->flush();
                $this->addFlash("success", "Album został dodany");
                return $this->redirect($this->generateUrl('Galeria'));

            }

            return $this->render(':intranet:album.html.twig', array(
                'album' => $album,
                'userType' => $userType,
                'form' => $form->createView()
            ));
        }
        else {
            return $this->render(':intranet:album.html.twig', array(
                'album' => $album,
                'userType' => "ROLE_USER",
                'form' => $form->createView()
            ));
        }
    }
    /**
     * @Route("/album/{albumName}", name="intranet_gallery")
     */
    public function galleryAction($albumName, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c.albumId FROM AppBundle:IntraAlbum c WHERE c.albumName = '". $albumName."'"
        );
        $ct = $query->getArrayResult();
        $id = $ct[0]["albumId"];
        $gallery = $this->getDoctrine()
            ->getRepository('AppBundle:IntraGallery')
            ->findBy(array('albumId'=>$id));

        $user = $this->get('security.token_storage')->getToken()->getUsername();

        $galleryForm = new IntraGallery();
        $form = $this->createForm(addGallery::class, $galleryForm);

        if ($user != "anon.") {

            #$file_s = $this->getDoctrine()->getRepository('AppBundle:IntraUser')->findOneBy(array('userName' => $user));

            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                "SELECT c.userType FROM AppBundle:IntraUser c WHERE c.userName ='" . $user . "'"
            );
            $userType = $query->getArrayResult();
            $user = new IntraUser();
            $userType = $userType[0]['userType'];
            $userId = $user->getUserId();

            $galleryForm->setAlbumId($id);
            $galleryForm->setGalleryCreatorId(1);
            $galleryForm->setGalleryModId(1);
            $galleryForm->setGalleryDateAdd(new \DateTime('now', (new \DateTimeZone('Europe/Warsaw'))));
            $galleryForm->setGalleryDateMod(new \DateTime('now', (new \DateTimeZone('Europe/Warsaw'))));
            $form = $this->createForm(addGallery::class, $galleryForm);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $file = $galleryForm->getGalleryImages();
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );
                $galleryForm->setGalleryImages($fileName);
                $em = $this->getDoctrine()->getManager();
                $em->persist($galleryForm);
                $em->flush();
                $this->addFlash("success", "Zdjęcie zostało dodane");
                return $this->redirect($this->generateUrl('intranet_gallery', ['albumName' => $albumName]));

            }

            return $this->render(':intranet:gallery.html.twig', array(
                'gallery' => $gallery,
                'userType' => $userType,
                'albumName' => $albumName,
                'galleryForm' => $form->createView()
            ));
        }
        else {
            return $this->render(':intranet:gallery.html.twig', array(
                'gallery' => $gallery,
                'userType' => "ROLE_USER",
                'albumName' => $albumName,
                'galleryForm' => $form->createView()
            ));
        }
    }


    public function urlAction(Request $request)
    {
        $route = $request->get('_route');
        $url = $request->getUri();
    }

    /**
     * @Route("/album/edit/{albumName}")
     */
    public function editAlbumAction($albumName, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c FROM AppBundle:IntraAlbum c WHERE c.albumName = '". $albumName."'"
        );
        $ct = $query->getArrayResult();
        $ct = $ct[0];
        $albumForm = new IntraAlbum();
        $form = $this->createForm(editAlbum::class, $albumForm);
        $form->get('albumName')->setData($ct['albumName']);
        $form->get('albumDesc')->setData($ct['albumDesc']);
        $form->handleRequest($request);
        $post = $em->getRepository('AppBundle:IntraAlbum')->find($ct['albumId']);
        /** @var $post IntraAlbum */
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAlbumName($albumForm->getAlbumName());
            $post->setAlbumDesc($albumForm->getAlbumDesc());
            $file = $albumForm->getAlbumImage();
            if(isset($file)) {

                $image = $ct['albumImage'];
                $file_path = $this->getParameter('image_directory').'/'.$image;
                if(file_exists($file_path)) unlink($file_path);

                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );

                $albumForm->setAlbumImage($fileName);
                $post->setAlbumImage($fileName);
            }
            $post->setAlbumDateAdd($ct['albumDateAdd']);
            $post->setAlbumDateMod(new \DateTime('now', (new \DateTimeZone('Europe/Warsaw'))));
            $post->setAlbumCreatorId($ct['albumCreatorId']);
            $post->setAlbumModId($ct['albumModId']);
            $post->getAlbumName();
            $post->getAlbumDesc();

            $em->flush();
            $this->addFlash("success", "Album został uaktualniony");
            return $this->redirect($this->generateUrl('Galeria'));

        }

        return $this->render('intranet/editAlbum.html.twig', array(
            'album' => $ct,
            'editForm' => $form->createView()
        ));
    }

    /**
     * @Route("/deleteGallery/{galleryId}", name="deleteGallery")
     */
    public function deleteGalleryAction($galleryId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $gallery = $em->getRepository('AppBundle:IntraGallery')->find($galleryId);

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c.galleryImages, c.albumId FROM AppBundle:IntraGallery c WHERE c.galleryId ='".$galleryId."'"
        );

        $galleryImages = $query->getArrayResult();
        $image = $galleryImages[0]['galleryImages'];
        $albumId = $galleryImages[0]['albumId'];

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c.albumName FROM AppBundle:IntraAlbum c WHERE c.albumId ='".$albumId."'"
        );
        $album = $query->getArrayResult();
        $albumName = $album[0]['albumName'];

        $file_path = $this->getParameter('image_directory').'/'.$image;
        if(file_exists($file_path)) unlink($file_path);
        if (!$gallery) {
            $this->addFlash("danger", "Wystąpił błąd");
            return $this->redirect($this->generateUrl('intranet_gallery', ['albumName' => $albumName]));
        }

        $em->remove($gallery);
        $em->flush();
        $this->addFlash("success", "Zdjęcie zostało pomyślnie usunięte");
        return $this->redirect($this->generateUrl('intranet_gallery', ['albumName' => $albumName]));
    }

    /**
     * @Route("/deleteAlbum/{albumId}", name="deleteAlbum")
     */
    public function deleteAlbumAction($albumId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $album = $em->getRepository('AppBundle:IntraAlbum')->find($albumId);

        $em2 = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c.albumImage FROM AppBundle:IntraAlbum c WHERE c.albumId ='".$albumId."'"
        );

        $albumImage = $query->getArrayResult();
        $image = $albumImage[0]['albumImage'];
        $file_path = $this->getParameter('image_directory').'/'.$image;
        if(file_exists($file_path)) unlink($file_path);
        if (!$album) {
            $this->addFlash("danger", "Wystąpił błąd");
            return $this->redirectToRoute('Galeria');
        }

        $em->remove($album);
        $em->flush();
        $this->addFlash("success", "Album został pomyślnie usunięty");
        return $this->redirectToRoute('Galeria');
    }

}