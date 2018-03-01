<?php

namespace AppBundle\Controller;

use AppBundle\Entity\IntraUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class AdminController extends Controller
{

    /**
     * @Route("/admin", name="Admin")
     */
    public function adminAction(Request $request)
    {

        return $this->render('intranet/admin/admin.html.twig', array(
        ));

    }
}
