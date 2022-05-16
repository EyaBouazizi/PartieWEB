<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RecmobileController extends AbstractController
{
    /**
     * @Route("/recmobile", name="app_recmobile")
     */
    public function index(): Response
    {
        return $this->render('recmobile/index.html.twig', [
            'controller_name' => 'RecmobileController',
        ]);
    }

    /**
     *@Route ("/listreclamation" , name="list_reclamation")
     */
    function  getReclamations(ReclamationRepository  $repo, NormalizerInterface $Normalizer) {
        $Reclamations=$repo->findAll();
        $jsonContent=$Normalizer->normalize($Reclamations,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
  //one rec
    /**
     * @Route ("/reclamation/{id}" , name="eclamation_id")
     */
    public function  RecId(Request $request, $id ,NormalizerInterface $Normalizer ){
        $em = $this->getDoctrine()->getManager();
        $reclamation= $em->getRepository(reclamation::class)->find($id);
        $jsonContent=$Normalizer->normalize($reclamation ,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    // add
    /**
     * @Route ("/addRecJSON/new/" , name="addRecJSON" , methods={"GET" , "POST"})
     */
    public function addRecJSON (Request $request , NormalizerInterface $Normalizer){
        $em = $this->getDoctrine()->getManager();
        $reclamation = new Reclamation();
        $reclamation->setTitre($request->get('titre'));
        $reclamation->setMessage($request->get('message'));
        $reclamation->setEtat("Non traitée");
        $reclamation->setDateRec(new \DateTimeImmutable('now'));
        $em->persist($reclamation);
        $em->flush();
        $jsonContent=$Normalizer->normalize($reclamation,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
        /*http://127.0.0.1:8000/addRecJSON/new/?titre=nproblem&message=TesTTesTmayssa*/


    }

    // update

    /**
     * @Route("/updateRecJSON/{id}", name="updateRecJSON", methods={"GET", "POST"})
     */
    public function updateRecJSON(Request $request ,$id , NormalizerInterface $Nomalizer){
        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->find($id);
        $reclamation->setTitre($request->get('titre'));
        $reclamation->setMessage($request->get('message'));
        $reclamation->setEtat("Non traitée");
        $reclamation->setDateRec(new \DateTimeImmutable('now'));
        $em->flush();
        $jsonContent=$Nomalizer->normalize($reclamation,'json',['groups'=>'post:read']);
        return new Response("Club updated successfully".json_encode($jsonContent));

    }

    //delet
    /**
     * @Route("/deleteRecJSON/{id}", name="deleteRecJSON")
     */
    public  function  deleteRecJSON($id, Request $request , NormalizerInterface $Normalizer) {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->find($id);
        $em->remove($reclamation);
        $em->flush();
        $jsonContent=$Normalizer->normalize($reclamation,'json',['groups'=>'post:read']);
        return new Response("Reclamation deleted successfully".json_encode($jsonContent));


    }
}
