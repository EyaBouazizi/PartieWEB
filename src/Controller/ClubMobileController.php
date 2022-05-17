<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClubMobileController extends AbstractController
{
    /**
     * @Route("/club/mobile", name="club_mobile")
     */
    public function index(): Response
    {
        return $this->render('club_mobile/index.html.twig', [
            'controller_name' => 'ClubMobileController',
        ]);
    }
}
