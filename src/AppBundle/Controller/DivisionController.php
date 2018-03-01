<?php

namespace AppBundle\Controller;

use AppBundle\Entity\IntraDivision;
use AppBundle\Entity\IntraDivisionPosition;
use AppBundle\Entity\IntraDivisionPositionLink;
use AppBundle\Entity\IntraUser;
use AppBundle\Entity\IntraUserPositionLink;
use AppBundle\Form\addDepartment;
use AppBundle\Form\editDepartment;
use AppBundle\Form\editDivision;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class DivisionController extends Controller
{
    private function get_json_array($divisionName) {

        $query = $this->getDoctrine()->getManager()->createQueryBuilder('u')
            ->select('u.userId', 'u.userName', 'u.userLastname', 'dp.positionName', 'dp.positionId', 'd.divisionId')
            ->from('AppBundle:IntraUser', 'u')
            ->innerJoin('AppBundle:IntraUserPositionLink','upl', 'WITH', 'u.userId = upl.divisionLinkUid')
            ->innerJoin('AppBundle:IntraDivisionPositionLink', 'dpl', 'WITH', 'upl.divisionLinkPid = dpl.divisionPositionLinkPid')
            ->innerJoin('AppBundle:IntraDivisionPosition', 'dp', 'WITH', 'dp.positionId = dpl.divisionPositionLinkPid')
            ->innerJoin('AppBundle:IntraDivision', 'd', 'WITH', 'd.divisionId = dpl.divisionPositionLinkDid')
            ->where('d.divisionName = :divisionName')
            ->setParameter('divisionName', $divisionName)
            ->getQuery();

//        SELECT `user_id`, `user_name`, `position_name`, `division_name`
//FROM ((((`intra_user`
//INNER JOIN `intra_user_position_link` ON `division_link_uid`=`user_id`)
//INNER JOIN `intra_division_position_link` ON `division_link_pid`=`division_position_link_pid`)
//INNER JOIN `intra_division_position` ON `position_id`=`division_position_link_pid`)
//INNER JOIN `intra_division` ON `division_id`=`division_position_link_did`);

        $result = $query->getArrayResult();
        foreach ($result as $key => $row) {
            $arr[] =
                array(
                    "Nazwisko" => $row["userLastname"],
                    "Imie" => $row["userName"],
                    "id" => $row["userId"],
                    "positionName" => $row["positionName"],
                );
        }
        return $arr;
    }

    /**
     * @Route("get_division_positions/{divisionName}", name="getDivisionPositions")
     */
    public function getPositionsAction($divisionName){
        $query = $this->getDoctrine()->getManager()->createQueryBuilder('u')
            ->select('dp.positionName', 'dp.positionId')
            ->from('AppBundle:IntraDivisionPosition', 'dp')
            ->innerJoin('AppBundle:IntraDivisionPositionLink', 'dpl', 'WITH', 'dp.positionId = dpl.divisionPositionLinkPid')
            ->innerJoin('AppBundle:IntraDivision', 'd', 'WITH', 'd.divisionId = dpl.divisionPositionLinkDid')
            ->where('d.divisionName = :divisionName')
            ->setParameter('divisionName', $divisionName)
            ->getQuery();

//        SELECT `user_id`, `user_name`, `position_name`, `division_name`
//FROM ((((`intra_user`
//INNER JOIN `intra_user_position_link` ON `division_link_uid`=`user_id`)
//INNER JOIN `intra_division_position_link` ON `division_link_pid`=`division_position_link_pid`)
//INNER JOIN `intra_division_position` ON `position_id`=`division_position_link_pid`)
//INNER JOIN `intra_division` ON `division_id`=`division_position_link_did`);

        $result = $query->getArrayResult();
        foreach ($result as $key => $row) {
            $arr[] =
                array(
                    "positionId" => $row["positionId"],
                    "positionName" => $row["positionName"],
                );
        }

        return new Response(json_encode($arr, 200));
    }

    /**
     * @Route("get_division_users/{divisionName}", name="getDivisionUsers")
     */
    public function getUsersAction($divisionName){
        $arr = $this->get_json_array($divisionName);
        return new Response(json_encode($arr, 200));
    }
    /**
     * @Route("update_division_users/{divisionName}", name="updateDivisionUsers")
     */
    public function updateUsersAction(Request $request, $divisionName){
        $req = $request->query->get('models');
        $req = json_decode($req, true);
        $req = $req[0];
        if($req['positionName'] == '0'){
            return new Response(json_encode('Proszę wypełnić wszystkie pola', 200));
        }
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c.divisionId FROM AppBundle:IntraDivision c WHERE c.divisionName ='".$divisionName."'"
        );
        $result = $query->getArrayResult();
        $parentId = $result[0]['divisionId'];
        $query = $em->createQuery(
            "SELECT c.divisionName FROM AppBundle:IntraDivision c WHERE c.divisionId ='".$parentId."'"
        );
        $result = $query->getArrayResult();
        $parent = $result[0]['divisionName'];

        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:IntraUser')->find($req['id']);
        /** @var $post IntraDivision */
        $post->setUserName($req['Imie']);
        $post->setUserLastname($req['Nazwisko']);

        $em->flush();
        $post2 = $em->getRepository('AppBundle:IntraUserPositionLink')->findOneBy(array('divisionLinkUid' => $req['id']));
        /** @var $post2 IntraUserPositionLink */
        $post2->setDivisionLinkPid($req['positionName']);

        $em->flush();
        $this->addFlash("success", "Dział został uaktualniony");
        return $this->redirect($this->generateUrl('Dział', ['departmentName' => $parent, 'divisionName' => $divisionName]));

//        $arr = $this->get_json_array();
//        return new Response(json_encode($arr, 200));
    }
    /**
     * @Route("add_division_users/{divisionName}", name="addDivisionUsers")
     */
    public function errorAction(Request $request, $divisionName){
//        $arr = $this->get_json_array();
//        return new Response(json_encode($arr, 200));
    }
    /**
     * @Route("delete_division_users", name="deleteDivisionUsers")
     */
    public function deleteUsersAction(Request $request)
    {

        $req = $request->query->get('models');
        $req = json_decode($req, true);
        $req = $req[0];
//        $testfile = fopen($this->getParameter('docs_directory')."newfile_delete.txt", "w") or die("Unable to open file!");
//        fwrite($testfile, print_r($req , TRUE));
//        fclose($testfile);
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $query = $qb->delete('AppBundle:IntraUserPositionLink', 'upl')
            ->where('upl.divisionLinkUid = :id')
            ->setParameter('id', $req['id'])
            ->getQuery();
        $query->execute();

        return new Response(json_encode($req, true), 200);
    }

    /**
     * @Route("add_user_position/{divisionId}/{divisionName}/{departmentName}", name="dodaj stanowisko")
     */
    public function addUserPositionAction(Request $request, $divisionId, $divisionName, $departmentName)
    {

        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();



        /** @var $dp IntraDivisionPosition*/
        if($data != null){
            $dp = new IntraDivisionPosition();
            $dpl = new IntraDivisionPositionLink();
            $dp->setPositionName($data['position']);
            $dp->setPositionStatus('1');
            $em->persist($dp);
            $em->flush();
            $dpl->setDivisionPositionLinkPid($dp->getPositionId());
            $dpl->setDivisionPositionLinkDid($divisionId);
            $em->persist($dpl);
            $em->flush();

            return $this->redirect($this->generateUrl('Dział', ['departmentName' => $departmentName, 'divisionName' => $divisionName]));
        }

        return new Response(json_encode($data, true), 200);
    }
//    /**
//     * @Route("delete_division_users", name="deleteDivisionUsers")
//     */
//    public function deleteUsersAction(Request $request){
//        $arr = $this->get_json_array();
//        return new Response(json_encode($arr, 200));
//    }
}