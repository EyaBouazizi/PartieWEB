<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/club", methods={"GET"})
 */

class ClubController extends AbstractController
{
    /**
     * @Route("/clublist", name="app_club_index", methods={"GET"})
     */
    public function index(ClubRepository $clubRepository,EvenementRepository $evenementRepository): Response
    {
        return $this->render('club/index.html.twig', [
            'clubs' => $clubRepository->findAll(),
            'events' => $evenementRepository->findAll(),
        ]);

    }






    /**
     * @Route("/new", name="app_club_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ClubRepository $clubRepository): Response
    {
        $club = new Club();
        $club->setDateCreation(new \DateTimeImmutable());
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $club->getImageclb();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            /*$file->move($this->getParameter('images_directory'), $fileName);*/
            $file->move($this->getParameter('kernel.project_dir') .'/public/UserPart/imgs',$fileName);
            $club->setImageclb($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($club);
            $em->flush();

            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('club/new.html.twig', [
            'club' => $club,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_club_show", methods={"GET"})
     */
    public function show(Club $club): Response
    {
        return $this->render('club/show.html.twig', [
            'club' => $club,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_club_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Club $club, ClubRepository $clubRepository): Response
    {
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $club->getImageclb();
            //IMAGE
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            /*$file->move($this->getParameter('images_directory'), $fileName);*/
            $file->move($this->getParameter('kernel.project_dir') .'/public/UserPart/imgs',$fileName);
            $club->setImageclb($fileName);

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('club/edit.html.twig', [
            'club' => $club,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_club_delete", methods={"POST"})
     */
    public function delete(Request $request, Club $club, ClubRepository $clubRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$club->getId(), $request->request->get('_token'))) {
            $clubRepository->remove($club);
        }

        return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/showEventsNBR/{id}", name="showEventsCount")
     */
    public function CalculEv($nom_club)
    {   $club = $this->getDoctrine()->getRepository(Club::class)->findBy($nom_club);
        $nbrEvents= $this->getDoctrine()->getRepository(Evenement::class)->calculEvents($club->getId());
        return $this->render('club/show.html.twig', [
            "club" => $club,
            "NbrEvents"=>$nbrEvents]);
    }



}
