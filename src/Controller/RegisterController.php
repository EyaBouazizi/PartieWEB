<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\CodeType;
use App\Form\RegType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function reg(MailerInterface $mailer, Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(RegType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($encoded);
            $utilisateur->setType('user');
            $code = $this->generateRandomString();
            $this->sendCode($code, $utilisateur, $mailer);
            $this->container->get('session')->set('task', $utilisateur);
            $this->container->get('session')->set('code', $code);
            dump($code);

            return $this->redirectToRoute('app_register_confirm', ['utilisateur' => $utilisateur]);
        }

        return $this->render('utilisateur/registr.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }


    function generateRandomString($length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    function sendCode(string $code, Utilisateur $user, MailerInterface $mailer)
    {
        $email = (new Email())
            ->from(new Address('eagles.esprit@gmail.com', 'Kteby'))
            ->to($user->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text($code)
            ->html('<p>Insert Code</p>' . $code);

        $mailer->send($email);
    }

    /**
     * @Route("/register/confirm", name="app_register_confirm")
     */
    function confirm(MailerInterface $mailer, Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $cs = $request->request->get('_username');
        dump($cs);
        $formc = $this->createForm(CodeType::class);
        $formc->handleRequest($request);


        $utilisateur = $this->container->get('session')->get('task');
        $code = $this->container->get('session')->get('code');
        dump($code);
        dump($cs);
        $cs = $request->request->get('_username');
        dump($cs);
        dump($code == $cs);
        if ($code == $cs) {
            $this->addFlash('success', 'Bienvenue sur notre plateforme');
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            $this->addFlash('success', 'Bienvenue sur notre plateforme');
            return $this->redirectToRoute('app_login');

        } else {
            $this->addFlash('notice', 'Invalid name entered');
        }
        return $this->render('utilisateur/confirmcode.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $formc->createView(),
        ]);
    }

}
