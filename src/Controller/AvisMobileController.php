<?php

namespace App\Controller;
use App\Entity\Avis;
use App\Entity\Livre;
use App\Repository\AvisRepository;
use App\Repository\LivreRepository;
use App\Repository\UtilisateurRepository;
use Proxies\__CG__\App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/Avis")
 */
class AvisMobileController extends AbstractController
{
    /**
     * @Route("/mobile1", name="app_avis_mobile")
     */
    public function index(): Response
    {
        return $this->render('avis_mobile/index.html.twig', [
            'controller_name' => 'AvisMobileController',
        ]);
    }

    //LISTE EVENTS
    /**
     * @Route("/listAvisMo", name="list_avis")
     */
    function  getAvis(AvisRepository  $repo, NormalizerInterface $normalizer){

        $avi=$repo->findAll();
        $jsonContent=$normalizer->normalize($avi,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }


    //ONE CLUB
    /**
     * @Route("/AvisM/{id_avis}", name="Avisid")
     */
    public function  AisId(Request $request , $id_avis ,NormalizerInterface $Normalizer){

        $em = $this->getDoctrine()->getManager();
        $avi = $em->getRepository(Avis::class)->find($id_avis);
        $jsonContent=$Normalizer->normalize($avi ,'json',['groups'=>'post:read']);
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
 * @Route("/addAvisJSON/new/", name="addAvisJSON", methods={"GET", "POST"})
 */
public function addAvisJSON(Request $request, NormalizerInterface $Normalizer)
{
    $em = $this->getDoctrine()->getManager();
    $avi = new Avis();
    $avi->setCommentaire($request->get('commentaire'));
    $avi->setIdLivre($request->get('id_livre'));
    $avi->setIdUser($request->get('id_user'));

    $em->persist($avi);
    $em->flush();
    $jsonContent=$Normalizer->normalize($avi,'json',['groups'=>'post:read']);
    return new Response(json_encode($jsonContent));
    /*http://127.0.0.1:8000/clb/addClubJSON/new/?nom_club=nejet&club_owner=nojnoj&access=public&imageclb=f780fa4f92fa335b578ffe4c38829d50.png*/
}

    //UPDATE Avis

    /**
     * @Route("/updateAvisJSON/{id_avis}", name="updateAvisJSON", methods={"GET", "POST"})
     */
    public function updateAvisJSON($id_avis,Request $request, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $avi = $em->getRepository(Avis::class)->find($id_avis);
        $avi->setCommentaire($request->get('commentaire'));
        $em->flush();
        $jsonContent=$Normalizer->normalize($avi,'json',['groups'=>'post:read']);
        return new Response("Avis updated successfully".json_encode($jsonContent));
        /*http://127.0.0.1:8000/clb/updateClubJSON/22?nom_club=nejetx&description=xxxx&club_owner=xxxxx&imageclb=cfe44b89e1f73aa35a564f235121e914.png&access=public*/
    }

    //DELETE Avis

    /**
     * @Route("/deleteAvisJSON/{id_avis}", name="deleteAvisJSON")
     */
    public function deleteAvisJSON($id_avis,Request $request,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $avi = $em->getRepository(Avis::class)->find($id_avis);
        $em->remove($avi);
        $em->flush();

        $jsonContent=$Normalizer->normalize($avi,'json',['groups'=>'post:read']);
        return new Response("avis deleted successfully".json_encode($jsonContent));
        /*http://127.0.0.1:8000/clb/deleteClubJSON/22*/
    }





}
