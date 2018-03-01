<?php
/**
 * Created by PhpStorm.
 * User: gborycki
 * Date: 8/24/2017
 * Time: 7:54 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\IntraEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class eventsController extends Controller
{


    public function get_json_array()
    {

        $ems = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->from('AppBundle:IntraEvents', 'e')
            ->select("e.newsId, e.newsStart, e.newsEnd, e.newsStatus, e.newsAllday, e.newsTitle, e.newsText, e.newsUserId")
            ->where("e.newsType = 2")
            ->addOrderBy("e.newsId", "ASC")
            ->setMaxResults(10000)
            ->getQuery();
        $data = $ems->getArrayResult();

        foreach ($data as $key => $row) {
            $arr[] =
                array(
                    "id" => $row["newsId"],
                    "start" => $row["newsStart"]->format('Y-m-d H:i:s'),
                    "end" => $row["newsEnd"]->format('Y-m-d H:i:s'),
                    "title" => $row["newsTitle"],
                    "allday" => $row["newsAllday"],
                    "user" => $row["newsUserId"],
                    "status" => $row["newsStatus"],
                    "description" => $row["newsText"]
                );
        }
        if (isset($arr)){
            return $arr;
        }


    }


    /**
     * @Route("/events", name="Kalendarz")
     */
    public function eventsAction(Request $request)
    {
        $arr = $this->get_json_array();
        return $this->render('intranet/events.html.twig', array ('events_json' => json_encode($arr)));
    }



    /**
     * @Route("/events/get_events", name="geteventss")
     */
    public function json_page()
    {
        $arr = $this->get_json_array();
        return new Response(json_encode($arr), 200);
    }


    /**
     * @Route("/events/update", name="eventsupdate")
     */
    public function updateAction(Request $request)
    {


        $req = $request->request->all();

        if ($req) {

            $req = $req["models"];
            $req = json_decode($req, true);

            #$testfile = fopen($this->getParameter('docs_directory') . "newfile_update.txt", "w") or die("Unable to open file!");
            #fwrite($testfile, print_r($req, TRUE));
            #fclose($testfile);


            foreach ($req as $key => $row) {

                if ($row["id"] > 0) {
                    $em = $this->getDoctrine()->getManager();
                    $event = $em->getRepository('AppBundle:IntraEvents')->find($row["id"]);

                    $sInLocal = date("Y-m-d H:i:s", strtotime($row["start"].' UTC'));
                    $eInLocal = date("Y-m-d H:i:s", strtotime($row["end"].' UTC'));
                    $event->setNewsStart(new \DateTime($sInLocal));
                    $event->setNewsEnd(new \DateTime($eInLocal));

                    $event->setNewsTitle($row["title"]);

                    if (isset($row["status"])) {
                        if (is_array($row["status"])) {
                            $event->setnewsStatus($row["status"]["value"]);
                        } else {
                            $event->setnewsStatus($row["status"]);
                        }
                    }

                    $em->flush();
                }
            }
            return new Response(json_encode($req, true), 200);
        }
        else {
            return new Response('{ "errors": ["Brak danych", "Wprowadź dane"] }', 200);
        }
    }


    /**
     * @Route("/events/add", name="eventsadd")
     */
    public function addAction(Request $request)
    {
        $req = $request->request->all();
        $req = $req["models"];
        $req = json_decode($req, true);

        #$testfile = fopen($this->getParameter('docs_directory')."newfile_add.txt", "w") or die("Unable to open file!");
        #fwrite($testfile, print_r($req , TRUE));
        #fclose($testfile);

        foreach ($req as $key => $row) {
            $em = $this->getDoctrine()->getManager();
            $event = new IntraEvents();
            $event->setnewsDateAdd(new \DateTime(date('Y-m-d H:i:s'), (new \DateTimeZone('Europe/Warsaw'))));
            $event->setnewsDateMod(new \DateTime(date('Y-m-d H:i:s'), (new \DateTimeZone('Europe/Warsaw'))));
            $event->setNewsTitle($row["title"]);

            $sInLocal = date("Y-m-d H:i:s", strtotime($row["start"].' UTC'));
            $eInLocal = date("Y-m-d H:i:s", strtotime($row["end"].' UTC'));
            $event->setNewsStart(new \DateTime($sInLocal));
            $event->setNewsEnd(new \DateTime($eInLocal));

            $event->setnewsCreatorId($this->getUser()->getUserId());
            $event->setnewsUserId($this->getUser()->getUserId());
            $event->setnewsText($row["description"]);
            $event->setnewsShortText("");
            $event->setnewsImage("");
            $event->setnewsType("2");

            if (isset($row["status"])) {
                if (is_array($row["status"])) {
                    $event->setnewsStatus($row["status"]["value"]);
                } else {
                    $event->setnewsStatus($row["status"]);
                }
            }
            $em->persist($event);
            $em->flush();
        }


        return new Response(json_encode($req, true), 200);
    }



    /**
     * @Route("/events/delete", name="eventsdelete")
     */
    public function deleteAction(Request $request)
    {
        $req = $request->request->all();
        $req = $req["models"];
        $req = json_decode($req, true);

        #$testfile = fopen($this->getParameter('docs_directory')."newfile_delete.txt", "w") or die("Unable to open file!");
        #fwrite($testfile, print_r($req , TRUE));
        #fclose($testfile);

        foreach ($req as $key => $row) {

            if ($row["id"] > 0 ) {
                $out = " DELETE FROM AppBundle:IntraEvents c WHERE c.newsId = " . $row["id"];

                if (isset($out)) {
                    $em = $this->getDoctrine()->getManager();
                    $em = $em->createQuery($out);
                    $numDeleted = $em->execute();
                }

            }
        }


        return new Response(json_encode($req, true), 200);
    }


}
