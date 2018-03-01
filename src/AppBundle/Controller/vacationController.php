<?php

namespace AppBundle\Controller;

use AppBundle\Entity\IntraEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class vacationController extends Controller
{

    public function getData($uid=false, $group=false)
    {

        if ($uid) {
            $ems = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->from('AppBundle:IntraEvents', 'e')
                ->select("e.newsText, e.newsUserId, e.newsVacationType, e.newsAllday, e.newsId, e.newsText, e.newsStart, u.userName, e.newsEnd, e.newsStatus, u.userLastname, u.userId")
                ->leftJoin("AppBundle:IntraUser", "u", "WITH", "e.newsUserId=u.userId")
                ->where("e.newsType = 3 AND e.newsUserId=".$uid)
                ->addOrderBy("e.newsId", "DESC")
                ->setMaxResults(10000)
                ->getQuery();
        }
        else {
            $ems = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->from('AppBundle:IntraEvents', 'e')
                ->select("e.newsText, e.newsUserId, e.newsVacationType, e.newsAllday, e.newsId, e.newsText, e.newsStart, u.userName, e.newsEnd, e.newsStatus, u.userLastname, u.userId")
                ->leftJoin("AppBundle:IntraUser", "u", "WITH", "e.newsUserId=u.userId")
                ->where("e.newsType = 3")
                ->addOrderBy("e.newsId", "DESC")
                ->setMaxResults(10000)
                ->getQuery();
        }

        $data = $ems->getArrayResult();
        return $data;
    }


    public function genJson($uid=false, $group=false) {

        $data = $this->getData($uid, $group);
        foreach ($data as $key => $row) {
            $arr[] =
                array(
                    "id" => $row["newsId"],
                    "start" => $row["newsStart"]->format('Y-m-d H:i:s'),
                    "end" => $row["newsEnd"]->format('Y-m-d H:i:s'),
                    "title" => $row["userName"].' '.$row["userLastname"],
                    "status" => $row["newsStatus"],
                    "user" => $row["newsUserId"],
                    "type" => $row["newsVacationType"],
                    "description" => $row["newsText"],
                    "allday" => $row["newsAllday"],
                );
        }
        if (isset($arr)){
            return $arr;
        }
    }


    /**
     * @Route("/user_vacation", name="UrlopyUser")
     */
    public function getUserVacationData() {
        $uid = $this->getUser()->getUserId();
        $vfull = $this->getVacationDays($uid);
        $vused = $this->checkVacationDays($uid, 0);
        $vrem = $vfull - $vused;
        $data = '{"vfull":"'.$vfull.'","vused":"'.$vused.'","vrem":"'.$vrem.'"}';
        return new Response($data, 200);
    }

    /**
     * @Route("/vacation", name="Urlopy")
     */
    public function showVacations() {

        $uid = $this->getUser()->getUserId();
        $check = $this->genJson($uid);
        $vfull = $this->getVacationDays($uid);
        $vused = $this->checkVacationDays($uid, 1);
        $vrem = $vfull - $vused;
        return $this->render('intranet/vacation.html.twig', array('vfull' => $vfull, 'vused' => $vused, 'vrem' => $vrem, 'vcheck' => $check));
    }

    /**
     * @Route("/vacation/get_data", name="vacation_get_data")
     */
    public function getjData()
    {


        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $arr = $this->genJson();
        }
        else {
            $userId = $this->getUser()->getUserId();
            $arr = $this->genJson($userId);
        }
        if ($arr) {
            return new Response(json_encode($arr), 200);
        }
        else {
            return new Response('', 200);
        }

    }

    private function getCurrentVacationEventstatus($id) {
        $ems = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->from('AppBundle:IntraEvents', 'e')
            ->select("e.newsStatus")
            ->where("e.newsId = ".$id)
            ->getQuery();
        $data = $ems->getArrayResult();
        return $data[0]["newsStatus"];
    }

    private function getCurrentVacationEventDays($id) {
        $ems = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->from('AppBundle:IntraEvents', 'e')
            ->select("e.newsStart, e.newsEnd")
            ->where("e.newsId = ".$id)
            ->getQuery();
        $data = $ems->getArrayResult();
        $days = $this->howmanydays($data[0]["newsStart"]->format("Y-m-d H:i:s"), $data[0]["newsEnd"]->format("Y-m-d H:i:s"));
        return $days;
    }

    public function getVacationDays($uid) {
        $ems = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->from('AppBundle:IntraUser', 'u')
            ->select("u.userVacationDays")
            ->where("u.userId = ".$uid)
            ->getQuery();
        $data = $ems->getArrayResult();
        return $data[0]["userVacationDays"];
    }


    private function compareVacationDays($uid, $type=false) {
        $vacationDays = $this->getVacationDays($uid);
        $howManyVacationDays = $this->checkVacationDays($uid, $type);
        $vacation = $vacationDays - $howManyVacationDays;
        return $vacation;
    }

    public function checkVacationDays($uid, $type=false) {

        if (isset($type)) {
            $ems = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->from('AppBundle:IntraEvents', 'v')
                ->select("v.newsId, v.newsStart, v.newsEnd")
                ->where("v.newsType = 3 AND v.newsStatus = '".$type."' AND v.newsUserId = ".$uid)
                ->getQuery();
        }
        else {
            $ems = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->from('AppBundle:IntraEvents', 'v')
                ->select("v.newsId, v.newsStart, v.newsEnd")
                ->where("v.newsType = 3 AND v.newsUserId = ".$uid)
                ->getQuery();
        }

        $data = $ems->getArrayResult();

        $days = 0;
        foreach ($data as $key => $row) {
            $dateStart = $row["newsStart"]->format("Y-m-d H:i:s");
            $dateEnd = $row["newsEnd"]->format("Y-m-d H:i:s");
            $days = $days + ($this->howmanydays($dateStart, $dateEnd));
        }
        return $days;
    }


    private function howmanydays($dateStart, $dateEnd) {
        $datetime1 = new \DateTime($dateStart);
        $datetime2 = new \DateTime($dateEnd);
        $interval = $datetime1->diff($datetime2);
        return $interval->format('%a');
    }

    /**
     * @Route("/vacation/add", name="vacation_add")
     */
    public function addAction(Request $request)
    {
        $req = $request->request->all();
        if ($req) {

            $req = $req["models"];
            $req = json_decode($req, true);

            foreach ($req as $key => $row) {

                $sInLocal = date("Y-m-d H:i:s", strtotime($row["start"].' UTC'));
                $eInLocal = date("Y-m-d H:i:s", strtotime($row["end"].' UTC +1 day'));
                $nowplandays = $this->howmanydays($sInLocal, $eInLocal);

                $currentVacationStatus = $this->compareVacationDays($this->getUser()->getUserId(),0);
                $sum = $currentVacationStatus - $nowplandays;

                if ($sum < 0) {

                    return new Response('{ "errors": ["Urlop przekracza ilość dostępnych dni o ", "' . abs($sum) . '"] }', 200);

                }
                else {

                    $em = $this->getDoctrine()->getManager();
                    $event = new IntraEvents();
                    $event->setNewsStart(new \DateTime($sInLocal));
                    $event->setNewsEnd(new \DateTime($eInLocal));
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
                return new Response(json_encode($req, true), 200);

            }

        }
        else {
            return new Response('{ "errors": ["Brak danych", "Wprowadź dane"] }', 200);
        }
    }


    /**
     * @Route("/vacation/update", name="vacation_update")
     */
    public function updateAction(Request $request)
    {

        $req = $request->request->all();

        if ($req) {

            $req = $req["models"];
            $req = json_decode($req, true);


            foreach ($req as $key => $row) {

                if ($row["id"] > 0) {

                    $dbstatus = $this->getCurrentVacationEventstatus($row["id"]);
                    $sInLocal = date("Y-m-d H:i:s", strtotime($row["start"].' UTC'));
                    $eInLocal = date("Y-m-d H:i:s", strtotime($row["end"].' UTC'));
                    $nowplandays = $this->howmanydays($sInLocal, $eInLocal);
                    $currentEventDays = $this->getCurrentVacationEventDays($row["id"]);
                    $currentVacationStatus = $this->compareVacationDays($this->getUser()->getUserId(), $row["status"]);
                    $cutDays = $currentVacationStatus + $currentEventDays;
                    $sum = $cutDays - $nowplandays;

                    if (isset($row["status"])) {
                        if (is_array($row["status"])) {
                            $status = $row["status"]["value"];
                        } else {
                            $status = $row["status"];
                        }
                    }

                    if (isset($row["type"])) {
                        if (is_array($row["type"])) {
                            $type = $row["type"]["value"];
                        } else {
                            $type = $row["type"];
                        }
                    }

                    if ($sum < 0) {
                        return new Response('{ "errors": ["Urlop przekracza ilość dostępnych dni o ", "' . abs($sum) . '"] }', 200);
                    }
                    else {
                        if ($dbstatus == 1){
                            return new Response('{ "errors": ["Urlop jest już potwierdzony", " "] }', 200);
                        }
                        else {
                            $em = $this->getDoctrine()->getManager();
                            $event = $em->getRepository('AppBundle:IntraEvents')->find($row["id"]);
                            $event->setNewsTitle($row["title"]);
                            $event->setNewsText($row["description"]);
                            $event->setNewsDateMod(new \DateTime(date('Y-m-d H:i:s'), (new \DateTimeZone('Europe/Warsaw'))));

                            $event->setNewsStart(new \DateTime($sInLocal));
                            $event->setNewsEnd(new \DateTime($eInLocal));

                            if ($status) {
                                $event->setnewsStatus($status);
                            }

                            if (isset($type)) {
                                $event->setNewsVacationType($type);
                            }

                            $em->flush();
                            return new Response(json_encode($req, true), 200);
                        }
                    }
                }
            }

        }
        else {
            return new Response('{ "errors": ["Brak danych", "Wprowadź dane"] }', 200);
        }
    }







    private function getDivisionData($uid) {
        $query = $this->getDoctrine()->getManager()->createQueryBuilder('u')
            ->select('dp.positionName', 'dp.positionId', 'dep.departmentName', 'dep.departmentId', 'div.divisionName', 'div.divisionId')
            ->from('AppBundle:IntraUserPositionLink', 'upl')
            ->innerJoin('AppBundle:IntraDivisionPosition', 'dp', 'WITH', 'dp.positionId = upl.divisionLinkPid')
            ->innerJoin('AppBundle:IntraDivisionPositionLink', 'dpl', 'WITH', 'dpl.divisionPositionLinkPid = dp.positionId')
            ->innerJoin('AppBundle:IntraDivision', 'div', 'WITH', 'dpl.divisionPositionLinkDid = div.divisionId')
            ->innerJoin('AppBundle:IntraDivisionDepartmentLink', 'ddl', 'WITH', 'ddl.divisionDepartmentLinkDivisionId = div.divisionId')
            ->innerJoin('AppBundle:IntraDepartment', 'dep', 'WITH', 'ddl.divisionDepartmentLinkDepartmentId = dep.departmentId')
            ->where('upl.divisionLinkUid = :userId')
            ->setParameter('userId', $uid)
            ->getQuery();
        $result = $query->getArrayResult();
        return $result;
    }



    /**
     * @Route("/pdf", name="gen_pdfs")
     */

    private function pdfGetData($nid=false)
    {
        $ems = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->from('AppBundle:IntraEvents', 'e')
            ->select("e.newsText, e.newsUserId, e.newsVacationType, e.newsAllday, e.newsId, e.newsText, e.newsStart, u.userName, e.newsEnd, e.newsStatus, u.userLastname, u.userId")
            ->leftJoin("AppBundle:IntraUser", "u", "WITH", "e.newsUserId=u.userId")
            ->where("e.newsId=".$nid)
            ->getQuery();
        $data = $ems->getArrayResult();
        return $data;
    }



    /**
     * @Route("/pdf/{nid}", name="gen_pdf")
     */
    public function pdfGenIndex($nid)
    {
        $data = $this->pdfGetData($nid);
        $uid = $data[0]["userId"];
        $divdata = $this->getDivisionData($uid);
        $check = $this->genJson($uid);
        $vfull = $this->getVacationDays($uid);
        $vused = $this->checkVacationDays($uid, 1);
        $vrem = $vfull - $vused;
        $snappy = $this->get('knp_snappy.pdf');

        if (!$divdata) {
            $html = $this->renderView('intranet/pdfvacationdocument.html.twig', array(
                'name' => $data[0]["userName"] . ' ' . $data[0]["userLastname"],
                'date' => $data[0]["newsStart"]->format('d.m.Y'),
                'currentdate' => date('d.m.Y'),
                'positionName' => '',
                'departmentName' => '',
                'divisionName' => '',
                'vfull' => $vfull,
                'vused' => $vused,
                'vrem' => $vrem,
                'vcheck' => $check
            ));
        }
        else {
            $html = $this->renderView('intranet/pdfvacationdocument.html.twig', array(
                'name' => $data[0]["userName"] . ' ' . $data[0]["userLastname"],
                'date' => $data[0]["newsStart"]->format('d.m.Y'),
                'currentdate' => date('d.m.Y'),
                'positionName' => $divdata[0]["positionName"],
                'departmentName' => $divdata[0]["departmentName"],
                'divisionName' => $divdata[0]["divisionName"],
                'vfull' => $vfull,
                'vused' => $vused,
                'vrem' => $vrem,
                'vcheck' => $check
            ));
        }

        $filename = 'myFirstSnappyPDF';
        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }


}
