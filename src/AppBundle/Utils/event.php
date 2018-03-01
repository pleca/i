<?php
/**
 * Created by PhpStorm.
 * User: gborycki
 * Date: 2017-09-13
 * Time: 08:12
 */

namespace AppBundle\Utils;

use AppBundle\Entity\IntraEvents;
use AppBundle\Entity\IntraUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\AbstractType;

class event extends Controller
{


    public function eventAdd($request) {
        $req = $request->request->all();
        $req = $req["models"];
        $req = json_decode($req, true);

        #$testfile = fopen($this->getParameter('docs_directory')."newfile_add.txt", "w") or die("Unable to open file!");
        #fwrite($testfile, print_r($req , TRUE));
        #fclose($testfile);

        foreach ($req as $key => $row) {
            $em = $this->getDoctrine()->getManager();
            $event = new IntraEvents();
            $event->setNewsStart(new \DateTime($row["start"], (new \DateTimeZone('Europe/Warsaw'))));
            $event->setNewsStart(new \DateTime($row["start"], (new \DateTimeZone('Europe/Warsaw'))));
            $event->setNewsEnd(new \DateTime($row["end"], (new \DateTimeZone('Europe/Warsaw'))));
            $event->setNewsTitle($row["title"]);
            $event->setnewsDateAdd(new \DateTime(date('Y-m-d H:i:s'), (new \DateTimeZone('Europe/Warsaw'))));
            $event->setnewsDateMod(new \DateTime(date('Y-m-d H:i:s'), (new \DateTimeZone('Europe/Warsaw'))));
            $event->setnewsCreatorId($this->getUser()->getUserId());
            $event->setnewsUserId($this->getUser()->getUserId());
            $event->setnewsText("");
            $event->setnewsShortText("");
            $event->setnewsImage("");
            $event->setnewsType("3");
            $event->setnewsStatus("0");
            $em->persist($event);
            $em->flush();
        }

        return $req;
    }



}