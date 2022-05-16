<?php

namespace App\Controller;
use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer;  
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
 use Symfony\Component\Serializer\Serializer;
class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="app_categorie")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categorie = $entityManager
            ->getRepository(Categorie::class)
            ->findAll();
        return $this->render('categorie/index.html.twig', [
            'b'=> $categorie
        ]);
    }
     /**
     * @Route("/addcategorie", name="addcategorie")
     */
    public function addCattegorie(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/add.html.twig', [
            'categorie' => $categorie,
            'f' => $form->createView(),
        ]);
    }

    /**
     * @Route("/deletecategorie/{id}", name="deletecategorie")
     */
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorie);
            $em->flush();
        

        return $this->redirectToRoute('app_categorie', [], Response::HTTP_SEE_OTHER);
    }
      /**
     * @Route("/editcategorie/{id}", name="editcategorie")
     */
    public function editCattegorie(Request $request, EntityManagerInterface $entityManager,$id): Response
    {
        $categorie= $this-> getDoctrine()->getManager()->getRepository(Categorie::class)->find($id);
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'f' => $form->createView(),
        ]);
    }
    /**
     * @Route("/cat", name="cat")
     */
    public function showAction(EntityManagerInterface $entityManager,FlashyNotifier $flashy){

        $categorie = $entityManager
            ->getRepository(Categorie::class)
            ->findAll();
        return $this->render('categorie/pages.html.twig', [
            'c'=> $categorie
        ]);
    }
    //ADD EVENT
    /**
     * @Route("/addcatJSON", name="actt")
     */
    public function addcatJSON(Request $request)
    {
         $categorie = new Categorie();
         $categorie = $request->query->get('nomCategorie');
         
         $em = $this->getDoctrine()->getManager();
         

         $categorie->setNomCategorie($categorie);
         

        
        
        $em->persist($categorie);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($categorie);
        return new Response("Club updated successfully".json_encode($formatted));
        /*http://127.0.0.1:8000/clb/addClubJSON/new/?nom_club=nejet&club_owner=nojnoj&access=public&imageclb=f780fa4f92fa335b578ffe4c38829d50.png*/
    }
}
