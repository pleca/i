<?php

namespace AppBundle\Controller;

use AppBundle\Entity\IntraUser;
use AppBundle\Entity\IntraDivisionPosition;
use AppBundle\Entity\IntraVacation;
use AppBundle\Entity\IntraUserPositionLink;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class UserManagementController extends Controller
{

    /**
 * @Route("/get_user_divisions", name="pobierz działy")
 */
    public function getUserDivisionsAction(Request $request) {
        $data = $request->request->get('department');

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c.divisionDepartmentLinkDepartmentId, c.divisionDepartmentLinkDivisionId FROM AppBundle:IntraDivisionDepartmentLink c WHERE c.divisionDepartmentLinkDepartmentId ='".$data."'"
        );
        $result = $query->getArrayResult();
        $ddl = $result;

        foreach ($ddl as $key => $row) {
            $query = $em->createQuery(
                "SELECT c.divisionName, c.divisionId FROM AppBundle:IntraDivision c WHERE c.divisionId = '".$row['divisionDepartmentLinkDivisionId']."'"
            );
            $result = $query->getArrayResult();
            $divisions[$key] = $result;
        }

        return new JsonResponse($divisions);

    }
    /**
     * @Route("/get_user_positions", name="pobierz pozycje")
     */
    public function getUserPositionsAction(Request $request) {
        $data = $request->request->get('division');

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c.divisionPositionLinkPid, c.divisionPositionLinkDid FROM AppBundle:IntraDivisionPositionLink c WHERE c.divisionPositionLinkDid ='".$data."'"
        );
        $result = $query->getArrayResult();
        $dpl = $result;

        foreach ($dpl as $key => $row) {
            $query = $em->createQuery(
                "SELECT c.positionName, c.positionId FROM AppBundle:IntraDivisionPosition c WHERE c.positionId = '".$row['divisionPositionLinkPid']."'"
            );
            $result = $query->getArrayResult();
            $positions[$key] = $result;
        }

        return new JsonResponse($positions);

    }

    /**
     * @Route("/user_management", name="Zarządzanie użytkownikami")
     */
    public function userManagementAction(Request $request) {

        $user = $this->get('security.token_storage')->getToken()->getUsername();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c.userType FROM AppBundle:IntraUser c WHERE c.userName ='".$user."'"
        );
        $userType = $query->getArrayResult();
        $userType = $userType[0]['userType'];

        $query = $this->getDoctrine()->getManager()->createQueryBuilder('u')
            ->select('u.userId', 'u.userLogin', 'u.userPassword', 'u.userName', 'u.userLastname', 'u.userEmail', 'u.userType', 'u.userActive', 'u.userDateAdd')
            ->from('AppBundle:IntraUser', 'u')
            ->getQuery();

        $result = $query->getArrayResult();
        //print_r($result);

        foreach ($result as $key => $row) {
            $arr[] =
                array(
                    "Nazwisko" => $row["userLastname"],
                    "Imie" => $row["userName"],
                    "Login" => $row["userLogin"],
                    "Email" => $row["userEmail"],
                    "Dodany" => $row["userDateAdd"]->format('Y-m-d H:i:s'),
                    "id" => $row["userId"],
                );
        }
        $query = $em->createQuery(
            "SELECT c.departmentName, c.departmentId FROM AppBundle:IntraDepartment c "
        );
        $result = $query->getArrayResult();
        $departments = $result;

        $query = $em->createQuery(
            "SELECT c.divisionPositionLinkPid, c.divisionPositionLinkDid FROM AppBundle:IntraDivisionPositionLink c "
        );
        $result = $query->getArrayResult();
        $dpl = $result;
        $query = $em->createQuery(
            "SELECT c.positionName, c.positionId FROM AppBundle:IntraDivisionPosition c "
        );
        $result = $query->getArrayResult();
        $positions = $result;
        return $this->render('intranet/admin/userManagement.html.twig',array(
            'users' => $arr,
            'departments' => $departments,
            'positions' => $positions,
            'dpl' => $dpl,
            'userType' => $userType
        ));
    }


    /**
     * @Route("/user_add", name="dodaj użytkownika")
     */
    public function userAddAction(Request $request, UserPasswordEncoderInterface $encoder) {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();

        /** @var $user IntraUser */
        /** @var $user2 IntraUserPositionLink */
        if($data != null){
            $user2 = new IntraUserPositionLink();
            $user = new IntraUser();
            $user->setUserName($data['user_name']);
            $user->setUserLastname($data['user_lastname']);
            $user->setUserLogin($data['user_login']);
            $user->setUserEmail($data['user_email']);
            $encoded = $encoder->encodePassword($user, $data['user_password']);
            $user->setUserPassword($encoded);
            $user->setUserType('ROLE_USER');
            $user->setUserActive('1');
            $user->setUserDateAdd(new \DateTime('now', (new \DateTimeZone('Europe/Warsaw'))));
            $user->setUserDateLastlogin(new \DateTime('now', (new \DateTimeZone('Europe/Warsaw'))));
            $user->setUserVacationDays($data['user_vacation']);
            $em->persist($user);
            $em->flush();
            $user->getUserId();

            $user2->setDivisionLinkUid($user->getUserId());
            $user2->setDivisionLinkPid($data['position']);
            $em->persist($user2);
            $em->flush();

            $this->addFlash("success", "Użytkownik został dodany");
            return $this->redirect($this->generateUrl('Zarządzanie użytkownikami'));
        }
    }

    /**
     * @Route("add_user_position", name="addUserPosition")
     */
    public function addUserPositionAction(Request $request)
    {

        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();

        $query = $this->getDoctrine()->getManager()->createQueryBuilder('u')
            ->select('dp.positionName', 'dp.positionId', 'dep.departmentName', 'dep.departmentId', 'div.divisionName', 'div.divisionId')
            ->from('AppBundle:IntraDivisionPosition', 'dp')
            ->innerJoin('AppBundle:IntraDivisionPositionLink', 'dpl', 'WITH', 'dpl.divisionPositionLinkPid = dp.positionId')
            ->innerJoin('AppBundle:IntraDivision', 'div', 'WITH', 'dpl.divisionPositionLinkDid = div.divisionId')
            ->innerJoin('AppBundle:IntraDivisionDepartmentLink', 'ddl', 'WITH', 'ddl.divisionDepartmentLinkDivisionId = div.divisionId')
            ->innerJoin('AppBundle:IntraDepartment', 'dep', 'WITH', 'ddl.divisionDepartmentLinkDepartmentId = dep.departmentId')
            ->where('dp.positionId = :positionId')
            ->setParameter('positionId', $data['position'])
            ->getQuery();
        $result = $query->getArrayResult();
        $user_position = $result;

        /** @var $upl IntraUserPositionLink */
        if($data != null){
            $upl = new IntraUserPositionLink();
            $upl->setDivisionLinkUid($data['userId']);
            $upl->setDivisionLinkPid($data['position']);
            $em->persist($upl);
            $em->flush();
        }
        return new Response(json_encode($user_position, true), 200);
    }

    /**
     * @Route("delete_division_users", name="deleteDivisionUsers")
     */
    public function deleteUsersAction(Request $request)
    {

        $req = $request->query->get('models');
        $req = json_decode($req, true);
        $req = $req[0];
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
     * @Route("/edit_user/{userId}", name="edytuj użytkownika")
     */
    public function editDepartmentAction($userId, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $data = $request->request->all();

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c FROM AppBundle:IntraUser c WHERE c.userId = '". $userId."'"
        );
        $result = $query->getArrayResult();
        $user = $result[0];

        $query = $em->createQuery(
            "SELECT c.departmentName, c.departmentId FROM AppBundle:IntraDepartment c "
        );
        $result = $query->getArrayResult();
        $departments = $result;

        $query = $this->getDoctrine()->getManager()->createQueryBuilder('u')
            ->select('dp.positionName', 'dp.positionId', 'dep.departmentName', 'dep.departmentId', 'div.divisionName', 'div.divisionId')
            ->from('AppBundle:IntraUserPositionLink', 'upl')
            ->innerJoin('AppBundle:IntraDivisionPosition', 'dp', 'WITH', 'dp.positionId = upl.divisionLinkPid')
            ->innerJoin('AppBundle:IntraDivisionPositionLink', 'dpl', 'WITH', 'dpl.divisionPositionLinkPid = dp.positionId')
            ->innerJoin('AppBundle:IntraDivision', 'div', 'WITH', 'dpl.divisionPositionLinkDid = div.divisionId')
            ->innerJoin('AppBundle:IntraDivisionDepartmentLink', 'ddl', 'WITH', 'ddl.divisionDepartmentLinkDivisionId = div.divisionId')
            ->innerJoin('AppBundle:IntraDepartment', 'dep', 'WITH', 'ddl.divisionDepartmentLinkDepartmentId = dep.departmentId')
            ->where('upl.divisionLinkUid = :userId')
            ->setParameter('userId', $userId)
            ->getQuery();
        $result = $query->getArrayResult();
        $user_positions = $result;
        /** @var $post IntraUser */
        if($data != null){
            $post = $em->getRepository('AppBundle:IntraUser')->findOneBy(array('userId'=>$userId));
            $post->setUserName($data['user_name']);
            $post->setUserLastname($data['user_lastname']);
            $post->setUserLogin($data['user_login']);
            $post->setUserEmail($data['user_email']);
            $post->setUserVacationDays($data['user_vacation']);
            if($data['user_password'])
            {
                $encoded = $encoder->encodePassword($post, $data['user_password']);
                $post->setUserPassword($encoded);
            }
            $em->flush();
            $this->addFlash("success", "Użytkownik został uaktualniony");
            return $this->redirect($this->generateUrl('Zarządzanie użytkownikami'));
        }

        return $this->render('intranet/admin/editUser.html.twig', array(
            'user' => $user,
            'departments' => $departments,
            'user_positions' => $user_positions
        ));
    }


    /**
     * @Route("/deleteUser/{userId}", name="deleteUser")
     */
    public function deleteUserAction($userId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('AppBundle:IntraUser')->find($userId);

        if (!$user) {
            $this->addFlash("danger", "Wystąpił błąd");
            return $this->redirectToRoute('Zarządzanie użytkownikami');
        }

        $qb = $em->createQueryBuilder();
        $query = $qb->delete('AppBundle:IntraUserPositionLink', 'upl')
            ->where('upl.divisionLinkUid = :id')
            ->setParameter('id', $userId)
            ->getQuery();
        $query->execute();

        $em->remove($user);
        $em->flush();
        $this->addFlash("success", "Użytkownik został pomyślnie usunięty");
        return $this->redirectToRoute('Zarządzanie użytkownikami');
    }
    /**
     * @Route("/deleteUserPosition/{userId}/{positionId}", name="deleteUserPosition")
     */
    public function deleteUserPositionAction($userId, $positionId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $position = $em->getRepository('AppBundle:IntraUserPositionLink')->findBy(array(
            'divisionLinkUid' => $userId,
            'divisionLinkPid' => $positionId
        ));

        if (!$position) {
            $this->addFlash("danger", "Wystąpił błąd");
            return $this->redirectToRoute('Zarządzanie użytkownikami');
        }

        $qb = $em->createQueryBuilder();
        $query = $qb->delete('AppBundle:IntraUserPositionLink', 'upl')
            ->where('upl.divisionLinkUid = :userId')
            ->andWhere('upl.divisionLinkPid = :positionId')
            ->setParameter('userId', $userId)
            ->setParameter('positionId', $positionId)
            ->getQuery();
        $query->execute();

        foreach($position as $key => $value){
            $em->remove($position[$key]);
        }

        //$em->remove($position);
        $em->flush();
        $this->addFlash("success", "Użytkownik został pomyślnie usunięty ze stanowiska");
        return $this->redirectToRoute('Zarządzanie użytkownikami');
    }
}
