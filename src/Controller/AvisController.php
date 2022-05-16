<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Evaluation;
use App\Entity\Utilisateur;
use App\Form\AvisType;
use App\Form\EvaluationType;
use App\Repository\AvisRepository;
use App\Repository\EvaluationRepository;
use App\Repository\LivreRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/avis")
 */
class AvisController extends AbstractController
{
    /**
     * @Route("/chart/stat", name="stat_evaluations_index", methods={"GET"})
     * @param AvisRepository $avisRepository
     * @param LivreRepository $livreRepository
     * @return Response
     */
    public function stat(AvisRepository $avisRepository,LivreRepository  $livreRepository): Response
    {
        $avis=$avisRepository->CountId();
        $livres=$livreRepository->findAll();

        $livresNames=[];
        $avisCount=[];

        foreach($avis as $item){
            foreach ($livres as $livre){
                if($livre->getIdLivre()==$item["id_livre"]){
                    array_push($livresNames,$livre->getTitre());
                    array_push($avisCount,$item["res"]);
                }
            }
        }

        return $this->render('evaluation/stat.html.twig', [
            'livresNames' => json_encode($livresNames),
            'avisCount' => json_encode($avisCount),

        ]);
    }

    /**
     * @Route("/", name="avis_index", methods={"GET","POST"})
     */
    public function index(UtilisateurRepository $utilisateurRepository,EvaluationRepository $evaluationRepository,LivreRepository $livreRepository, Request $request,AvisRepository $avisRepository, EntityManagerInterface $entityManager): Response
    {
        $evaluations = $evaluationRepository->findOneBy(['idLivre'=>29]);
        if(is_null($evaluations)==false) {
            $evaluations->setIsEvaluated(1);
        }else{
            $evaluations=new Evaluation();
            $evaluations->setIsEvaluated(0);
        }

        $avi = new Avis();
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livre=$livreRepository->find(29);
            $idUser= $entityManager->getRepository(Utilisateur::class)->findOneBy(['username' => $this->getUser()->getUsername()]);;
            $avi->setIdUser($idUser);
            $avi->setIdLivre($livre);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($avi);
            $entityManager->flush();

            return $this->redirectToRoute('avis_index', [], Response::HTTP_SEE_OTHER);
        }
        $evaluation = new Evaluation();
        $form1 = $this->createForm(EvaluationType::class, $evaluation);
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $livre=$livreRepository->find(29);
            $evaluation->setIdLivre($livre);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($evaluation);
            $entityManager->flush();

            return $this->redirectToRoute('evaluation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis/index.html.twig', [
            'avi' => $avi,
            'evaluation'=> $evaluation,
            'form' => $form->createView(),
            'avis' => $avisRepository->findBy(['idLivre' => 29]),
            'evaluations' => $evaluations,
            'form1'=>$form1->createView(),
        ]);


    }

    /**
     * @Route("/admin/{id}", name="avis_index_admin", methods={"GET","POST"})
     */
    public function indexAdmin($id,EvaluationRepository $evaluationRepository,LivreRepository $livreRepository,UtilisateurRepository $utilisateurRepository, Request $request,AvisRepository $avisRepository): Response
    {

        return $this->render('avis/indexBack.html.twig', [

            'avis' => $avisRepository->findBy(['idLivre' => $id]),

        ]);


    }
    /**
     * @Route("/new", name="avis_new", methods={"GET","POST"})
     */
    public function new(UtilisateurRepository $utilisateurRepository,LivreRepository $livreRepository,Request $request,AvisRepository $avisRepository,EvaluationRepository $evaluationRepository): Response
    {
        $evaluations = $evaluationRepository->findOneBy(['idLivre'=>29]);
        if(is_null($evaluations)==false) {
            $evaluations->setIsEvaluated(1);
        }else{
            $evaluations=new Evaluation();
            $evaluations->setIsEvaluated(0);
        }

        $avi = new Avis();
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livre=$livreRepository->find(29);
            $idUser=$utilisateurRepository->find(8);
            $avi->setIdUser($idUser);
            $avi->setIdLivre($livre);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($avi);
            $entityManager->flush();

            return $this->redirectToRoute('avis_index', [], Response::HTTP_SEE_OTHER);
        }
        $evaluation = new Evaluation();
        $form1 = $this->createForm(EvaluationType::class, $evaluation);
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $livre=$livreRepository->find(29);
            $evaluation->setIdLivre($livre);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($evaluation);
            $entityManager->flush();

            return $this->redirectToRoute('evaluation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis/new.html.twig', [
            'avi' => $avi,
            'evaluation'=> $evaluation,
            'form' => $form->createView(),
            'avis' => $avisRepository->findBy(['idLivre' => 29]),
            'evaluations' => $evaluations,
            'form1'=>$form1->createView(),
        ]);

    }

    /**
     * @Route("/{idAvis}", name="avis_show", methods={"GET"})
     */
    public function show(Avis $avi): Response
    {
        return $this->render('avis/show.html.twig', [
            'avi' => $avi,
        ]);
    }

    /**
     * @Route("/{idAvis}/edit", name="avis_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Avis $avi,AvisRepository $avisRepository): Response
    {
        $evaluation=new Evaluation();
        $form1 = $this->createForm(EvaluationType::class, $evaluation);
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evaluation_index', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($avi);
            $entityManager->flush();

            return $this->redirectToRoute('avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis/edit.html.twig', [
            'evaluation' => $evaluation,
            'form1' => $form1->createView(),
            'avi' => $avi,
            'avis' => $avisRepository->findBy(['idLivre' => 29]),
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("/{idAvis}", name="avis_delete", methods={"POST"})
     */
    public function delete(Request $request, Avis $avi): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getIdAvis(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($avi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('avis_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/admin/delete/{idAvis}", name="avis_delete_admin", methods={"POST","GET"})
     */
    public function deleteAdmin(Request $request, Avis $avi): Response
    {
        $idLivre=$avi->getIdLivre()->getIdLivre();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($avi);
            $entityManager->flush();


        return $this->redirectToRoute('avis_index_admin', ['id'=>$idLivre]);
    }
}
