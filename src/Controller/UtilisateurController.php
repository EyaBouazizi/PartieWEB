<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegType;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/utilisateur")
 */
class UtilisateurController extends AbstractController
{
    /**
     * @Route("/", name="app_utilisateur_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $utilisateurs = $entityManager
            ->getRepository(Utilisateur::class)
            ->findAll();
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }

    /**
     * @Route("/new", name="app_utilisateur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($encoded);
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create", name="app_utilisateur_reg", methods={"GET", "POST"})
     */
    public function reg(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(RegType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($encoded);
            $utilisateur->setType('user');
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/registr.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{idUser}", name="app_utilisateur_show", methods={"GET"})
     */
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @Route("/{idUser}/edit", name="app_utilisateur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(RegType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($encoded);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idUser}", name="app_utilisateur_delete", methods={"POST"})
     */
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getIdUser(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{idUser}/ban", name="app_utilisateur_ban", methods={"GET", "POST"})
     * @throws \Doctrine\DBAL\Exception
     */
    public function ban(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('ban' . $utilisateur->getIdUser(), $request->request->get('_token'))) {

            $queryBuilder = $entityManager->createQueryBuilder();
            $query = $queryBuilder->update('App\Entity\Utilisateur', 'u')
                ->set('u.type', ':type')
                ->where('u.idUser = :editId')
                ->setParameter('type', 'bannis')
                ->setParameter('editId', $utilisateur->getIdUser())
                ->getQuery();
            $query->execute();
            dump($query);
        }
        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{idUser}/unban", name="app_utilisateur_unban", methods={"GET", "POST"})
     * @throws \Doctrine\DBAL\Exception
     */

    public function unban(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('unban' . $utilisateur->getIdUser(), $request->request->get('_token'))) {

            $queryBuilder = $entityManager->createQueryBuilder();
            $query = $queryBuilder->update('App\Entity\Utilisateur', 'u')
                ->set('u.type', ':type')
                ->where('u.idUser = :editId')
                ->setParameter('type', 'user')
                ->setParameter('editId', $utilisateur->getIdUser())
                ->getQuery();
            $query->execute();
            dump($query);
        }
        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);

    }

    public function findUserByUsername($username)
    {
        return $this->getDoctrine()->getRepository(Utilisateur::class)->createQueryBuilder('utilisateur')
            ->where('utilisateur.username LIKE :username')
            ->setParameter('username', '%' . $username . '%')
            ->getQuery()
            ->getResult();
    }

}
