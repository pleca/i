<?php

namespace AppBundle\Controller;

use AppBundle\Entity\IntraUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class loginController extends Controller
{

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {

        $errors = $authenticationUtils->getLastAuthenticationError();
        $lastusername = $authenticationUtils->getLastUsername();



        return $this->render('intranet/login.html.twig', array(
            'errors' => $errors,
            'lastusername' => $lastusername
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction() {

    }

}
