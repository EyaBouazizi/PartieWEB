<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Repository\ClubRepository;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
/**
 * @Route("/event")
 */
class TestEventController extends AbstractController
{
    /**
     * @Route("/test/event", name="app_test_event")
     */
    public function index(): Response
    {
        return $this->render('test_event/index.html.twig', [
            'controller_name' => 'TestEventController',
        ]);
    }


    //LISTE EVENTS
    /**
     * @Route("/listeevent", name="list_event")
     */
    function  getEvents(EvenementRepository $repo, NormalizerInterface $Normalizer){

        $evenements=$repo->findAll();
        $jsonContent=$Normalizer->normalize($evenements,'json',['groups'=>'post:read']);
       /*return $this ->render('evenement/jsonlistEvent.html.twig',[
           'data' =>$jsonContent,
       ]);*/
        return new Response(json_encode($jsonContent));
    }

    //ONE EVENT
    /**
     * @Route("/Evenement/{id}", name="eventid")
     */
    public function  EventId(Request $request , $id ,NormalizerInterface $Normalizer){

        $em = $this->getDoctrine()->getManager();
        $evenement = $em->getRepository(Evenement::class)->find($id);
        $jsonContent=$Normalizer->normalize($evenement,'json',['groups'=>'post:read']);
        /*return $this ->render('evenement/jsonlistEvent.html.twig',[
            'data' =>$jsonContent,
        ]);*/
        return new Response(json_encode($jsonContent));
    }

   //ADD EVENT

    /**
     * @Route("/addEventJSON/new/{id}", name="addEventJSON", methods={"GET" ,"POST"})
     */
    public function addEventJSON($id,Request $request, NormalizerInterface $Normalizer,ClubRepository $repo)
    {
        $em = $this->getDoctrine()->getManager();
        $evenement = new Evenement();
        $evenement->setEventName($request->get('event_name'));
        $evenement->setDescription($request->get('description'));
        $evenement->setAdresse($request->get('adresse'));
        $evenement->setEventDate(new \DateTime('now'));
        $evenement->setImage($request->get('image'));
        $evenement->setClub($repo->findOneBy(['id' => $id]));
        //$evenement->setClub($repo->findOneBy(['id'=> $id]));
        $em->persist($evenement);
        $em->flush();
        $jsonContent = $Normalizer->normalize($evenement, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
        /*http://127.0.0.1:8000/event/addEventJSON/new/2?event_name=xxx&description=xxxx&adresse=xxxxx&image=cfe44b89e1f73aa35a564f235121e914.png*/
    }
    //UPDATE EVENT

    /**
     * @Route("/updateEventJSON/{id_event}/{id}", name="updateEventJSON", methods={"GET", "POST"})
     */
    public function updateEventJSON($id,$id_event,Request $request, NormalizerInterface $Normalizer,ClubRepository $repo,EvenementRepository $repevent)
    {
        $em = $this->getDoctrine()->getManager();
        $evenement = $em->getRepository(Evenement::class)->find($id_event);
        $evenement->setEventName($request->get('event_name'));
        $evenement->setDescription($request->get('description'));
        $evenement->setAdresse($request->get('adresse'));
        $evenement->setEventDate(new \DateTime('now'));
        $evenement->setImage($request->get('image'));
        $evenement->setClub($repo->findOneBy(['id'=> $id]));
        $em->flush();
        $jsonContent=$Normalizer->normalize($evenement,'json',['groups'=>'post:read']);
        return new Response("Evenement updated successfully".json_encode($jsonContent));
        /*http://127.0.0.1:8000/event/updateEventJSON/36/2?event_name=x&description=xxxx&adresse=xxxxx&club_id=2&image=cfe44b89e1f73aa35a564f235121e914.png*/
    }

    //DELETE EVENT

    /**
     * @Route("/deleteEventJSON/{id_event}", name="deleteEventJSON")
     */
    public function deleteEventJSON($id_event,Request $request,EvenementRepository $evenementRepository,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $evenement = $em->getRepository(Evenement::class)->find($id_event);
            $em->remove($evenement);
            $em->flush();

        $jsonContent=$Normalizer->normalize($evenement,'json',['groups'=>'post:read']);
        return new Response("Evenement deleted successfully".json_encode($jsonContent));
        /*http://127.0.0.1:8000/deleteEventJSON/4*/
        }






}
