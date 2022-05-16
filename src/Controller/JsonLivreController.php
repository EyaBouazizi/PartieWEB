<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
 use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\HttpFoundation\JsonResponse;


class JsonLivreController extends AbstractController
{
    /**
     * @Route("/test/club", name="app_test_club")
     */
    public function index(): Response
    {
        return $this->render('test_club/index.html.twig', [
            'controller_name' => 'TestClubController',
        ]);
    }

    //LISTE EVENTS
    /**
     * @Route("/listlivre", name="list_club")
     */
    function  getLivre(){

        $livre=$this->getDoctrine()->getManager()->getRepository(Livre::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($livre);
        /*return $this ->render('club/jsonlistClub.html.twig',[
            'data' =>$jsonContent,
        ]);*/
        return new Response(json_encode($formatted));
    }


    //ONE CLUB
    /**
     * @Route("/livre/{id}", name="clubid")
     */
    public function  ClubId(Request $request , $id ,NormalizerInterface $Normalizer){

        $em = $this->getDoctrine()->getManager();
        $livre = $em->getRepository(Livre::class)->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($livre);
        /*return $this ->render('club/jsonlistClub.html.twig',[
            'data' =>$jsonContent,
        ]);*/
        return new Response(json_encode($formatted));
    }

    

    /**
     * @Route("/addLivreJSON", name="addLivreJSON",methods={"GET", "POST"})
     */
    public function addLivreJSON(Request $request)
    {
       

         $livre = new Livre();
         $titre = $request->query->get('titre');
         $description = $request->query->get("description");
         $auteur = $request->query->get("auteur");
         $date = new \DateTime('now');
         $textField = $request->query->get('textLivre');

         $livre->setTitre($titre);
         $livre->setDescription($description);
         $livre->setAuteur($auteur);
         $livre->setDate($date);
         $livre->setImage("placeholder");
         $livre->setTextLivre($textField);

        
        $em = $this->getDoctrine()->getManager();
        $em->persist($livre);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($livre);
        return new Response("livre successfully".json_encode($formatted));
        
    }

    //UPDATE CLUB

        /**
         * @Route("/updateClubJSON/{id}", name="updateClubJSON", methods={"GET", "POST"})
         */
        public function updateClubJSON($id,Request $request, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository(Club::class)->find($id);
        $club->setNomClub($request->get('nom_club'));
        $club->setDateCreation(new \DateTimeImmutable('now'));
        $club->setClubOwner($request->get('club_owner'));
        $club->setAccess($request->get('access'));
        $club->setImageclb($request->get('imageclb'));
        $em->flush();
        $jsonContent=$Normalizer->normalize($club,'json',['groups'=>'post:read']);
        return new Response("Club updated successfully".json_encode($jsonContent));
        /*http://127.0.0.1:8000/clb/updateClubJSON/22?nom_club=nejetx&description=xxxx&club_owner=xxxxx&imageclb=cfe44b89e1f73aa35a564f235121e914.png&access=public*/
    }

        //DELETE CLUB

        /**
         * @Route("/deleteLivreJSON/{id}", name="deleteLivreJSON")
         */
        public function deleteLivreJSON($id,Request $request,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $livre = $em->getRepository(Livre::class)->find($id);
        $em->remove($livre);
        $em->flush();

        $serialize = new Serializer([new ObjectNormalizer()]);
             $formatted = $serialize->normalize("livre a ete supprimee avec success.");
             return new JsonResponse($formatted);
       /*http://127.0.0.1:8000/clb/deleteClubJSON/22*/
    }

// ‪C:\Users\eya\Desktop\book2.PNG



}
