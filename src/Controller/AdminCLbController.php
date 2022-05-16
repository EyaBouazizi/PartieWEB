<?php

namespace App\Controller;
use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/clbadmin")
 */
class AdminCLbController extends AbstractController
{
    /**
     * @Route("/adminclb", name="app_adminclb_index")
     */
    public function index(ClubRepository $clubRepository,Request $request,PaginatorInterface $paginator): Response
    {
        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $donnees = $this->getDoctrine()->getRepository(Club::class)->findBy([],['nom_club' => 'asc']);

        $clubs = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('admin_c_lb/index.html.twig', [
            'clubs' => $clubs,
        ]);
    }


    /**
     * @Route("/{id}", name="app_adminclb_show", methods={"GET"})
     */
    public function show(Club $club): Response
    {
        return $this->render('admin_c_lb/adminclbshow.html.twig', [
            'club' => $club,
        ]);
    }
    /**
     * @param ClubRepository $repo
     * @return Response
     * @Route ("/AfficheClb", name= "AfficheClbs")
     */
    public function afficheClubs(ClubRepository  $repo,Request $request, PaginatorInterface $paginator){
        // $repo = $this->getDoctrine()->getRepository(Evenement::class);

        $clubs= $repo->findAll();
        $clubs = $paginator->paginate(
            $clubs, // Requête contenant les données à paginer (ici nos clubs)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            1 // Nombre de résultats par page
        );

        return $this->render('evenement/afficheEvenementPublic.html.twig',
            ['clubs'=>$clubs]);


    }


    /**
     * @Route("/{id}", name="app_clbadmin_delete", methods={"POST"})
     */
    public function delete(Request $request, Club $club, ClubRepository $clubRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$club->getId(), $request->request->get('_token'))) {
            $em=$this->getDoctrine()->getManager();
            $em->remove($club);
            $em->flush();

        }

        return $this->redirectToRoute('app_adminclb_index', [], Response::HTTP_SEE_OTHER);
    }



}
