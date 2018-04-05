<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/shop")
 */
class ShopController extends Controller
{
    /**
     * @Route("/list", name="list")
     */
    public function listAction()
    {
        return $this->render(':intranet/Shop:list.html.twig', []);
    }
}
