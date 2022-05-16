<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\UploadedFile;
use App\Form\EvenementType;
use App\Form\EventReportType;
use App\Repository\EvenementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/evenement")
 */
class EvenementController extends AbstractController
{
    /**
     * @Route("/eventlist", name="evenement_index", methods={"GET"})
     */
    public function index(EvenementRepository $evenementRepository,Request $request,PaginatorInterface $paginator): Response
    {
        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $donnees = $this->getDoctrine()->getRepository(Evenement::class)->findBy([],['event_name' => 'asc']);

        $evenements = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos evenements)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    /*
    /**
     * @Route("/eventli", name="evenement_list", methods={"GET"})

    public function indexAdmin(EvenementRepository $evenementRepository): Response
    {
        return $this->render('dashboardAdmin/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    } */




    /**
     * @Route("/new", name="evenement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EvenementRepository $evenementRepository): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $evenement->getImage();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            /*$file->move($this->getParameter('images_directory'), $fileName);*/
            $file->move($this->getParameter('kernel.project_dir') .'/public/AdminPart/images',$fileName);
            $evenement->setImage($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();
            $this->addFlash('success', 'Evenement ajouté avec succes !');

            return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id_event}", name="evenement_show", methods={"GET"})
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    /**
     * @Route("edit/{id_event}", name="evenement_edit", methods={"GET", "POST"})

     */
    public function edit(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $evenement->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('kernel.project_dir') .'/public/AdminPart/images',$fileName);
            $evenement->setImage($fileName);

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id_event}", name="evenement_delete", methods={"POST"})
     */
    public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId_event(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($evenement);
            $em->flush();
        }

        return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("report/{id_event}", name="evenement_report", methods={"GET", "POST"})

     */

   /* public function report(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        $form = $this->createForm(EventReportType::class, $evenement);
       /* $form = $this->createFormBuilder($evenement)
            ->add('event_date', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Reporter Evenement']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/report.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }*/



    /*
     * @Route("/RechByNom", name="searchEvent" , methods={"POST"})
     */
    public  function  rechercherByNom(Request $request): Response
    {
        $em=$this->getDoctrine()->getManager();
        $events=$em->getRepository(Evenement::class)->findAll();
        if($request->isMethod("POST"))
        {
            $nom = $request->get('event_name');
            $events=$em->getRepository(Evenement::class)->findBy(array('event_name'=>$nom));
        }

        return $this->render("evenement/search.html.twig",
            array('evenements'=>$events));


    }


    /*
     * @Route("/search", name="searchBar" , methods={"POST"})
     */
   /*public function searchingEvent(EvenementRepository  $repo, Request $request)
    {
        //get('event_name') c'est le name a mettre dans le input search,
        //$key recupere l'entréee de la recherche
        $key = $request->get('event_name');


        $search = $repo->findByWord($key);
        if (!($search)) {
            $search = 'la recherche sur '.$key.' n\'a donnée aucun résultat';
        }elseif ($key === '') {
            $search = 'veuillez entrez une recherche';
        }
        return $this->render('evenement/search.html.twig', [
            'evenement'=>$search
        ]);
    }*/



}
