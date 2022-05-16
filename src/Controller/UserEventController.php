<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Participant;
use App\Entity\Utilisateur;
use App\Form\EvenementType;
use App\Repository\ClubRepository;
use App\Repository\EvenementRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/eventuser")
 */
class UserEventController extends AbstractController
{
    /**
     * @Route("/userevt", name="app_user_event")
     */
    public function index(EvenementRepository  $evenementRepository): Response
    {
        return $this->render('user_event/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="new_evenement_user", methods={"GET", "POST"})
     */
    public function new($id,Request $request,ClubRepository $clubRepository, EvenementRepository $evenementRepository): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //IMAGE
            $file = $evenement->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('kernel.project_dir') .'/public/AdminPart/images',$fileName);
            $evenement->setImage($fileName);

            $evenement->setClub($clubRepository->findOneBy(['id'=> $id]));
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();


            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_event/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id_event}", name="app_usrevent_show", methods={"GET"})
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('user_event/usereventshow.html.twig', [
            'evenement' => $evenement,
        ]);
    }


    /**
     * @Route("/all/{id}", name="app_usrevent_showall", methods={"GET"})
     */
    public function show2(Evenement $evenement,EvenementRepository $revent,ParticipantRepository $Rparticipant,$id): Response
    {
        return $this->render('user_event/index.html.twig', [
            'evenements' => $revent->findby(array
            ('club'=>$id)
            ),
            'parts' => $Rparticipant->findAll()
        ]);
    }


    /**
     * @param(idu)
     * @param(ide)
     * @Route("/rejo/{ide}/{idu}", name="rejo", methods={"GET", "POST"})
     * */

    public function new2($idu,$ide,ParticipantRepository $participantRepository,Request $request,EntityManagerInterface $entityManager): Response
    {

        $parp = new Participant();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['username' => $this->getUser()->getUsername()]);
        $parp->setIdu($user->getIdUser());
        $parp->setIde($ide);
        //$parp->setIde($ide);
        $entityManager->persist($parp);
        $entityManager->flush();



        return $this->redirectToRoute('app_usrevent_show', [
            'id_event'=>$ide
        ], Response::HTTP_SEE_OTHER);


    }

    /**
     * @Route("edit/{id_event}", name="evenement_edit", methods={"GET", "POST"})

     */
    public function edit(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //IMAGE
            $file = $evenement->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('kernel.project_dir') .'/public/AdminPart/images',$fileName);
            $evenement->setImage($fileName);

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('app_usrevent_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_event/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }





/*
    /**
     * @param Request $request
     * @route("evenement/Participer/{ide}" , name="participer")


    function Participer(Request $request, EvenementRepository $repository, $ide):Response{
        $participant=new Participant() ;
        $participant->setIde($ide) ;
        $participant->setIdu(1) ;
        $evenement=$repository->find($ide);
       // $evenement->setNbrparticiMax($nbrparticipants) ;
        $em=$this->getDoctrine()->getManager();
        $em->persist($participant);
        $em->persist($evenement);
        $em->flush();
        return $this->redirectToRoute("app_usrevent_show", [
            'id_event'=>$ide
        ], Response::HTTP_SEE_OTHER);


    }

    */


    /*
     * @Route("/participant/calcul", name="participant_calcul")

    public function calcul()
    {
        $repo = $this->getDoctrine()->getRepository(Evenement::class);
        $evenements = $repo->findAll();

        $nbrEvents = $repo->createQueryBuilder('e')
            ->select('COUNT(e.id_event) as nbrEvents')
            ->where()
            ->getQuery()
            ->getSingleScalarResult();

        $moyenneP = $somme / $compte;

        $output = array();

        foreach ($participant as $key => $part) {

            $prixP = $part->getPrix();
            $dette = $moyenneP - $prixP;
            $recup = $prixP - $moyenneP;

            $output[$key] = array(
                'nom' => $part->getNom(),
                'prenom' => $part->getPrenom(),
                'prix' => $part->getPrix(),
                'dette' => $dette,
                'recup' => $recup
            );
        }

        return $this->render('participant/calcul.html.twig', [
            'somme' => $somme,
            'compte' => $compte,
            'moyenneP' => $moyenneP,
            'output' => $output,
        ]);
    }*/

}
