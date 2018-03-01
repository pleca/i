<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\IntraUser;
use AppBundle\Entity\IntraEvents;

class pdfCreator extends Controller
{


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

    private function getData($nid=false)
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
    public function indexAction($nid)
    {
        $data = $this->getData($nid);

        $snappy = $this->get('knp_snappy.pdf');

        $html = $this->renderView('intranet/pdfvacationdocument.html.twig', array(
            'name' => $data[0]["userName"].' '.$data[0]["userLastname"],
            'date' => $data[0]["newsStart"]->format('d.m.Y'),
            'currentdate' => date('d.m.Y')
        ));

        $filename = 'myFirstSnappyPDF';
        var_dump($this->getDivisionData($data[0]["userId"]));

        /* return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        ); */
    }

}