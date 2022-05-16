<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/secure_path", name="app_login_role")
     */
    public function rediretUser(AuthenticationUtils $authenticationUtils): Response
    {
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true))
            return $this->redirect($this->generateUrl('app_utilisateur_index'));
        if (in_array('ROLE_USER', $this->getUser()->getRoles(), true))
            return $this->redirect($this->generateUrl('app_livre'));
        throw new \Exception(AccessDeniedException::class);

    }


}
