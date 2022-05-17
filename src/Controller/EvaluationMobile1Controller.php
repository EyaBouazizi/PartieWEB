<?php

namespace App\Controller;
use App\Entity\Evaluation;
use App\Entity\Livre;
use App\Repository\EvaluationRepository;
use App\Repository\LivreRepository;
use App\Repository\UtilisateurRepository;
use Proxies\__CG__\App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/Evaluation")
 */
class EvaluationMobile1Controller extends AbstractController
{
    /**
     * @Route("/mobile2", name="app_evaluation_mobile")
     */
    public function index(): Response
    {
        return $this->render('evaluation_mobile/index.html.twig', [
            'controller_name' => 'EvaluationMobile1Controller',
        ]);
    }

    //LISTE EVENTS
    /**
     * @Route("/listEvaluationMo", name="list_Evaluation")
     */
    function  getEvaluation(EvaluationRepository  $repo, NormalizerInterface $normalizer){

        $Eval=$repo->findAll();
        $jsonContent=$normalizer->normalize($Eval,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }


    //ONE CLUB
    /**
     * @Route("/EvaluationM/{id_evaluation}", name="Evaluationid")
     */
    public function  evaluationsId(Request $request , $id_evaluation ,NormalizerInterface $Normalizer){

        $em = $this->getDoctrine()->getManager();
        $Eval = $em->getRepository(Evaluation::class)->find($id_evaluation);
        $jsonContent=$Normalizer->normalize($Eval ,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    //ADD EVENT
    /*
        /**
         * @Route("/addAvisJSON/new/", name="addAvisjSON", methods={"GET", "POST"})
         */
    /* public function addAvisJSON(Request $request, NormalizerInterface $Normalizer,UtilisateurRepository $repo1,LivreRepository $repo,$id_livre,$id_user)
    {
        $em = $this->getDoctrine()->getManager();
        $avi = new Avis();
        $avi->setCommentaire($request->get('commentaire'));
        $avi->setIdLivre($repo->find(29));
        $avi->setIdUser($repo1->find(8));


        $em->persist($avi);
        $em->flush();
        $jsonContent=$Normalizer->normalize($avi,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
/*http://127.0.0.1:8001/Avis/addAvisJSON/new/?commentaire=bonuit*/

    /**
     * @Route("/addEvaluationMJSON/new/", name="addEvaluationMJSON", methods={"GET", "POST"})
     */
    public function addEvaluationJSON(Request $request, NormalizerInterface $Normalizer, LivreRepository $repo1)
    {
        $em = $this->getDoctrine()->getManager();
        $Eval = new Evaluation();
        $Eval-> setNbStars($request->get('nb_stars'));
        $Eval->setIdLivre($request->get('id_livre'));
        $livre=$repo1->find(29);
        $Eval->setIdLivre($livre);

        $em->persist($Eval);
        $em->flush();
        $jsonContent=$Normalizer->normalize($Eval,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
     /*http://127.0.0.1:8000/Evaluation/addEvaluationMJSON/new/?nb_stars=4&idLivre=29*/    }

    //UPDATE Avis

    /**
     * @Route("/updateEvaluationJSON/{id_Evaluation}", name="updateEvaluationJSON", methods={"GET", "POST"})
     */
    public function updateEvaluationJSON($id_Evaluation,Request $request, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $Eval= $em->getRepository(Evaluation::class)->find($id_Evaluation);
        $Eval-> setNbStars($request->get('nb_stars'));
        $em->flush();
        $jsonContent=$Normalizer->normalize($Eval,'json',['groups'=>'post:read']);
        return new Response("Eval updated successfully".json_encode($jsonContent));
/*http://127.0.0.1:8000/Evaluation/updateEvaluationJSON/31?nb_stars=2*/    }

    //DELETE Avis

    /**
     * @Route("/deleteEvaluationJSON/{id_Evaluation}", name="deleteEvaluationJSON")
     */
    public function deleteEvaluationJSON($id_Evaluation,Request $request,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $Eval = $em->getRepository(Evaluation::class)->find($id_Evaluation);
        $em->remove($Eval);
        $em->flush();

        $jsonContent=$Normalizer->normalize($Eval,'json',['groups'=>'post:read']);
        return new Response("Eval deleted successfully".json_encode($jsonContent));
        /*http://127.0.0.1:8000/Evaluation/deleteEvaluationJSON/31*/
    }





}
