<?php

namespace App\Controller;

use App\Entity\Club;
use App\Repository\ClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/clb")
 */
class TestClubController extends AbstractController
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
     * @Route("/listclub", name="list_club")
     */
    function  getClubs(ClubRepository  $repo, NormalizerInterface $Normalizer){

        $clubs=$repo->findAll();
        $jsonContent=$Normalizer->normalize($clubs,'json',['groups'=>'post:read']);
        /*return $this ->render('club/jsonlistClub.html.twig',[
            'data' =>$jsonContent,
        ]);*/
        return new Response(json_encode($jsonContent));
    }


    //ONE CLUB
    /**
     * @Route("/Club/{id}", name="clubid")
     */
    public function  ClubId(Request $request , $id ,NormalizerInterface $Normalizer){

        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository(Club::class)->find($id);
        $jsonContent=$Normalizer->normalize($club ,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    //ADD EVENT

    /**
     * @Route("/addClubJSON/new/", name="addClubJSON", methods={"GET", "POST"})
     */
    public function addClubJSON(Request $request, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $club = new Club();
        $club->setNomClub($request->get('nom_club'));
        $club->setDateCreation(new \DateTimeImmutable('now'));
        $club->setClubOwner($request->get('club_owner'));
        $club->setAccess($request->get('access'));
        $club->setImageclb($request->get('imageclb'));
        $em->persist($club);
        $em->flush();
        $jsonContent=$Normalizer->normalize($club,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
        /*http://127.0.0.1:8000/clb/addClubJSON/new/?nom_club=nejet&club_owner=nojnoj&access=public&imageclb=f780fa4f92fa335b578ffe4c38829d50.png*/
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
         * @Route("/deleteClubJSON/{id}", name="deleteClubJSON")
         */
        public function deleteClubJSON($id,Request $request,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository(Club::class)->find($id);
        $em->remove($club);
        $em->flush();

        $jsonContent=$Normalizer->normalize($club,'json',['groups'=>'post:read']);
        return new Response("Club deleted successfully".json_encode($jsonContent));
       /*http://127.0.0.1:8000/clb/deleteClubJSON/22*/
    }





}
