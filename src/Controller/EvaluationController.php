<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Evaluation;
use App\Form\AvisType;
use App\Form\EvaluationType;
use App\Repository\AvisRepository;
use App\Repository\EvaluationRepository;
use App\Repository\LivreRepository;
use App\Repository\UtilisateurRepository;
use JCrowe\BadWordFilter\Facades\BadWordFilter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/evaluation")
 */
class EvaluationController extends AbstractController
{


    /**
     * @Route("/", name="evaluation_index", methods={"GET","POST"})
     * @param PaginatorInterface $paginator
     * @param UtilisateurRepository $utilisateurRepository
     * @param LivreRepository $livreRepository
     * @param Request $request
     * @param AvisRepository $avisRepository
     * @param EvaluationRepository $evaluationRepository
     * @return Response
     */
    public function index(PaginatorInterface $paginator,UtilisateurRepository $utilisateurRepository,LivreRepository $livreRepository,Request $request,AvisRepository $avisRepository,EvaluationRepository $evaluationRepository): Response
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
        $data=$avisRepository->findBy(['idLivre' => 29]);
        $avis=$paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            3
        );

        return $this->render('evaluation/index.html.twig', [
            'avi' => $avi,
            'evaluation'=> $evaluation,
            'form' => $form->createView(),
            'avis' => $avis,
            'evaluations' => $evaluations,
            'form1'=>$form1->createView(),
        ]);

    }



    /**
     * @Route("/", name="evaluation_new", methods={"GET","POST"})
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

        return $this->render('evaluation/new.html.twig', [
            'avi' => $avi,
            'evaluation'=> $evaluation,
            'form' => $form->createView(),
            'avis' => $avisRepository->findBy(['idLivre' => 29]),
            'evaluations' => $evaluations,
            'form1'=>$form1->createView(),
        ]);

    }

    /**
     * @Route("/show/{idEvaluation}/show", name="evaluation_show", methods={"GET"})
     */
    public function show(Evaluation $evaluation): Response
    {
        return $this->render('evaluation/show.html.twig', [
            'evaluation' => $evaluation,
        ]);
    }

    /**
     * @Route("/{idEvaluation}/edit", name="evaluation_edit", methods={"GET","POST"})
     */
    public function edit(AvisRepository $avisRepository,Request $request, Evaluation $evaluation): Response
    {
        $form1 = $this->createForm(EvaluationType::class, $evaluation);
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evaluation_index', [], Response::HTTP_SEE_OTHER);
        }

        $avi = new Avis();
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($avi);
            $entityManager->flush();

            return $this->redirectToRoute('avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evaluation/edit.html.twig', [
            'evaluation' => $evaluation,
            'form1' => $form1->createView(),
            'avi' => $avi,
            'avis' => $avisRepository->findBy(['idLivre' => 29]),
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("/{idEvaluation}/del", name="evaluation_delete", methods={"POST"})
     */
    public function delete(Request $request, Evaluation $evaluation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evaluation->getIdEvaluation(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($evaluation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('evaluation_index', [], Response::HTTP_SEE_OTHER);
    }
}
