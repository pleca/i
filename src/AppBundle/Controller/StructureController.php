<?php

namespace AppBundle\Controller;

use AppBundle\Entity\IntraDivision;
use AppBundle\Entity\IntraDepartment;
use AppBundle\Entity\IntraDivisionDepartmentLink;

use AppBundle\Entity\IntraDivisionPositionLink;
use AppBundle\Form\addDepartment;
use AppBundle\Form\addDivision;
use AppBundle\Form\editDepartment;
use AppBundle\Form\editDivision;
use AppBundle\Form\editDivisionDepartment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class StructureController extends Controller
{
    /**
     * @Route("/structure", name="Struktura")
     */
    public function structureAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUsername();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c.userType FROM AppBundle:IntraUser c WHERE c.userName ='".$user."'"
        );
        $userType = $query->getArrayResult();
        $userType = $userType[0]['userType'];

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c.departmentName, c.departmentId FROM AppBundle:IntraDepartment c"
        );
        $departments = $query->getArrayResult();

        $query = $em->createQuery(
            "SELECT c.userId, c.userName, c.userLastname FROM AppBundle:IntraUser c"
        );
        $result = $query->getArrayResult();

        foreach ($result as $key => $value){
            $result[$key]['userName'] = $result[$key]['userName'].' '.$result[$key]['userLastname'];
            $id = $result[$key]['userId'];
            $director[$id] = $result[$key]['userName'];
        }
        $departmentForm = new IntraDepartment();
        $form = $this->createForm(editDepartment::class, $departmentForm, array('departmentDirectorUid' => $director));
        $post = $em->getRepository('AppBundle:IntraDepartment');
        $form->handleRequest($request);
        $departmentForm->setDepartmentStatus('1');
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($departmentForm);
            $em->flush();
            $this->addFlash("success", "Departament został dodany");
            return $this->redirect($this->generateUrl('Struktura'));

        }


        return $this->render('intranet/admin/structure.html.twig', array(
            'departments' => $departments,
            'form' => $form->createView(),
            'userType' => $userType
        ));
    }

    /**
     * @Route("/structure/{departmentName}", name="Departament")
     */
    public function departmentAction($departmentName, Request $request) {

        $user = $this->get('security.token_storage')->getToken()->getUsername();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c.userType FROM AppBundle:IntraUser c WHERE c.userName ='".$user."'"
        );
        $userType = $query->getArrayResult();
        $userType = $userType[0]['userType'];

        $query = $em->createQuery(
            "SELECT c.departmentId, c.departmentDirectorUid FROM AppBundle:IntraDepartment c WHERE c.departmentName ='".$departmentName."'"
        );
        $result = $query->getArrayResult();
        $departmentId = $result[0]['departmentId'];

        $query = $em->createQuery(
            "SELECT c.userId, c.userName, c.userLastname FROM AppBundle:IntraUser c WHERE c.userId ='".$result[0]['departmentDirectorUid']."'"
        );
        $result = $query->getArrayResult();
        $director = "".$result[0]['userLastname']." ".$result[0]['userName']."";

        $query = $this->getDoctrine()->getManager()->createQueryBuilder('u')
            ->select('div.divisionName', 'div.divisionId')
            ->from('AppBundle:IntraDivision', 'div')
            ->innerJoin('AppBundle:IntraDivisionDepartmentLink', 'ddl', 'WITH', 'ddl.divisionDepartmentLinkDivisionId = div.divisionId')
            ->innerJoin('AppBundle:IntraDepartment', 'dep', 'WITH', 'ddl.divisionDepartmentLinkDepartmentId = dep.departmentId')
            ->where('dep.departmentId = :departmentId')
            ->setParameter('departmentId', $departmentId)
            ->getQuery();
        $divisions = $query->getArrayResult();

        $divisionForm = new IntraDivision();
        $form = $this->createForm(addDivision::class, $divisionForm);
        $form->handleRequest($request);
        $divisionForm->setDivisionStatus('1');

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($divisionForm);
            $em->flush();
            $divisionId = $divisionForm->getDivisionId();
            print_r($divisionId);
            $divisionDepartmentLink = new IntraDivisionDepartmentLink();
            $divisionDepartmentLink->setDivisionDepartmentLinkDepartmentId($departmentId);
            $divisionDepartmentLink->setDivisionDepartmentLinkDivisionId($divisionId);
            $em->persist($divisionDepartmentLink);
            $em->flush();
            $this->addFlash("success", "Dział został dodany");
            return $this->redirect($this->generateUrl('Departament', ['departmentName' => $departmentName]));

        }


        return $this->render('intranet/admin/department.html.twig', array(
            'director' => $director,
            'departmentName' => $departmentName,
            'divisions' => $divisions,
            'form' => $form->createView(),
            'userType' => $userType
        ));
    }

        /**
         * @Route("/structure/{departmentName}/{divisionName}", name="Dział")
         */
    public function employeesAction($departmentName, $divisionName, Request $request) {

        $user = $this->get('security.token_storage')->getToken()->getUsername();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c.userType FROM AppBundle:IntraUser c WHERE c.userName ='".$user."'"
        );
        $userType = $query->getArrayResult();
        $userType = $userType[0]['userType'];

        $query = $em->createQuery(
            "SELECT c.divisionId FROM AppBundle:IntraDivision c WHERE c.divisionName ='".$divisionName."'"
        );
        $result = $query->getArrayResult();
        $divisionId = $result[0]['divisionId'];
        $query = $em->createQuery(
            "SELECT c.divisionDepartmentLinkDepartmentId FROM AppBundle:IntraDivisionDepartmentLink c WHERE c.divisionDepartmentLinkDivisionId ='".$divisionId."'"
        );
        $result = $query->getArrayResult();
        $department = $result[0]['divisionDepartmentLinkDepartmentId'];
        $query = $em->createQuery(
            "SELECT c.departmentName FROM AppBundle:IntraDepartment c WHERE c.departmentId ='".$department."'"
        );
        $result = $query->getArrayResult();
        $department = $result[0]['departmentName'];

        return $this->render('intranet/admin/division.html.twig', array(
            'departmentName' => $department,
            'divisionName' => $divisionName,
            'divisionId' => $divisionId,
            'userType' => $userType
        ));
    }

    /**
     * @Route("/division/edit/{divisionName}", name="edytuj dział")
     */
    public function editDivisionAction($divisionName, Request $request)
    {

        $data = $request->request->all();

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c.divisionId, c.divisionName FROM AppBundle:IntraDivision c WHERE c.divisionName = '". $divisionName."'"
        );
        $ct = $query->getArrayResult();
        $ct = $ct[0];
        $query = $em->createQuery(
            "SELECT c.divisionDepartmentLinkDepartmentId FROM AppBundle:IntraDivisionDepartmentLink c WHERE c.divisionDepartmentLinkDivisionId ='".$ct['divisionId']."'"
        );
        $result = $query->getArrayResult();
        $parent = $result[0]['divisionDepartmentLinkDepartmentId'];
        $query = $em->createQuery(
            "SELECT c.departmentName, c.departmentId FROM AppBundle:IntraDepartment c WHERE c.departmentId = '".$parent."'"
        );
        $result = $query->getArrayResult();
        $parent = $result[0]['departmentName'];
        $parentId = $result[0]['departmentId'];
        $query = $em->createQuery(
            "SELECT c.departmentName, c.departmentId FROM AppBundle:IntraDepartment c "
        );
        $result = $query->getArrayResult();
        $departments = $result;

        /** @var $post IntraDivisionDepartmentLink */
        /** @var $post2 IntraDivision */
        if($data != null){
            if($data['department'] == 0){
                $data['department'] = $parentId;
            };
            $post = $em->getRepository('AppBundle:IntraDivisionDepartmentLink')->findOneBy(array('divisionDepartmentLinkDivisionId'=>$ct['divisionId']));
            $post2 = $em->getRepository('AppBundle:IntraDivision')->find($ct['divisionId']);
            $post->setDivisionDepartmentLinkDepartmentId($data['department']);
            $post2->setDivisionName($data['division']);
            $em->flush();
            $this->addFlash("success", "Dział został uaktualniony");
            return $this->redirect($this->generateUrl('Departament', ['departmentName' => $parent]));
        }

        return $this->render('intranet/admin/editDivision.html.twig', array(
            'division' => $ct,
            'departments' => $departments,
            'parent' => $parent,

        ));
    }

    /**
     * @Route("/department/edit/{departmentName}", name="edytuj departament")
     */
    public function editDepartmentAction($departmentName, Request $request)
    {
        $data = $request->request->all();

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT c.departmentId FROM AppBundle:IntraDepartment c WHERE c.departmentName = '". $departmentName."'"
        );
        $ct = $query->getArrayResult();
        $departmentId = $ct[0]['departmentId'];
        $query = $em->createQuery(
            "SELECT c.userId, c.userName, c.userLastname FROM AppBundle:IntraUser c"
        );
        $result = $query->getArrayResult();
        $users = $result;
        foreach ($users as $key =>$value ) {
            $users[$key]['userName'] = "" . $users[$key]['userLastname'] ." ". $users[$key]['userName'] . "";
        };

        $query = $this->getDoctrine()->getManager()->createQueryBuilder('u')
            ->select('u.userName', 'u.userLastname', 'u.userId', 'dep.departmentName', 'dep.departmentId')
            ->from('AppBundle:IntraUser', 'u')
            ->innerJoin('AppBundle:IntraDepartment', 'dep', 'WITH', 'dep.departmentDirectorUid = u.userId')
            ->where('dep.departmentId = :departmentId')
            ->setParameter('departmentId', $departmentId)
            ->getQuery();
        $result = $query->getArrayResult();
        $result = $result[0];
        $director = "".$result['userLastname']." ".$result['userName']."";
        $directorId = $result['userId'];


        /** @var $post IntraDepartment */
        if($data != null){
            if($data['director'] == 0){
                $data['director'] = $directorId;
            };
            $post = $em->getRepository('AppBundle:IntraDepartment')->findOneBy(array('departmentId'=>$departmentId));
            $post->setDepartmentName($data['department']);
            $post->setDepartmentDirectorUid($data['director']);
            $em->flush();
            $this->addFlash("success", "Departament został uaktualniony");
            return $this->redirect($this->generateUrl('Struktura'));
        }


       // print_r($users);
        return $this->render('intranet/admin/editDepartment.html.twig', array(
            'directorId' => $directorId,
            'director' => $director,
            'employees' => $users,
            'department' => $result,
        ));
    }

    /**
     * @Route("/deleteDivision/{divisionId}", name="deleteDivision")
     */
    public function deleteDivisionAction($divisionId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            "SELECT c.divisionDepartmentLinkDepartmentId FROM AppBundle:IntraDivisionDepartmentLink c WHERE c.divisionDepartmentLinkDivisionId = '". $divisionId."'"
        );
        $result = $query->getArrayResult();
        $result = $result[0];
        $departmentId = $result['divisionDepartmentLinkDepartmentId'];
        $query = $em->createQuery(
            "SELECT c.departmentName FROM AppBundle:IntraDepartment c WHERE c.departmentId = '". $departmentId."'"
        );
        $result = $query->getArrayResult();
        $result = $result[0];
        $departmentName = $result['departmentName'];
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder('u')
        ->delete('AppBundle:IntraDivisionDepartmentLink', 'ddl')
        ->where('ddl.divisionDepartmentLinkDivisionId = :divisionId')
        ->setParameter('divisionId', $divisionId)
        ->getQuery();
        $division = $qb->getArrayResult();
        if (!$division) {
            $this->addFlash("danger", "Wystąpił błąd");
            return $this->redirect($this->generateUrl('Departament', ['departmentName' => $departmentName]));
        }
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder('u')
            ->delete('AppBundle:IntraDivision', 'd')
            ->where('d.divisionId = :divisionId')
            ->setParameter('divisionId', $divisionId)
            ->getQuery();
        $division = $qb->getArrayResult();
        if (!$division) {
            $this->addFlash("danger", "Wystąpił błąd");
            return $this->redirect($this->generateUrl('Departament', ['departmentName' => $departmentName]));
        }

        $em->flush();
        $this->addFlash("success", "Dział został pomyślnie usunięty");
        return $this->redirect($this->generateUrl('Departament', ['departmentName' => $departmentName]));
    }

    /**
     * @Route("/deleteDepartment/{departmentId}", name="deleteDepartment")
     */
    public function deleteDepartmentAction($departmentId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $departament = $em->getRepository('AppBundle:IntraDepartment')->find($departmentId);

        if (!$departament) {
            $this->addFlash("danger", "Wystąpił błąd");
            return $this->redirectToRoute('Struktura');
        }

        $em->remove($departament);
        $em->flush();
        $this->addFlash("success", "Departament został pomyślnie usunięty");
        return $this->redirectToRoute('Struktura');
    }
}