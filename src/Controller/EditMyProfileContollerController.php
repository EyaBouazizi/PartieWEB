<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditMyProfileContollerController extends AbstractController
{
    /**
     * @Route("/edit/my/profile/contoller", name="app_edit_my_profile_contoller")
     */
    public function index(): Response
    {
        return $this->render('edit_my_profile_contoller/index.html.twig', [
            'controller_name' => 'EditMyProfileContollerController',
        ]);
    }
}
