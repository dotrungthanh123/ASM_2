<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/home', name: 'admin_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[IsGranted('ROLE_CUSTOMER')]
    #[Route('/home', name: 'customer_home')]
    public function cindex() {
        return $this->render('home/cindex.html.twig');
    }
}
