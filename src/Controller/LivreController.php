<?php

namespace App\Controller;
use App\Data\SearchData;
use App\Entity\Favoris;
use App\Entity\Livre;
use App\Entity\Utilisateur;
use App\Form\LivreType;
use App\Form\SearchForm;
use App\Repository\PostRepository;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer;
use Knp\Component\Pager\PaginatorInterface;


class LivreController extends AbstractController
{
    /**
     * @Route("/", name="app_livre")
     */
    public function index(): Response
    {

        $repository = $this->getDoctrine()->getRepository(Livre::class);
        $livre = $repository->findAll();
        $livres= $this-> getDoctrine()->getManager()->getRepository(Livre::class)->findByDate();
        return $this->render('livre/index.html.twig', [
            'books' => $livre,
            'b' => $livres

        ]);
    }
    /**
     * @Route("/try", name="try")
     */
    public function try(PostRepository $repository,Request $request)
    {
        $data = new SearchData();

        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);

        $livre = $repository->findSearch($data);
        return $this->render('livre/try.html.twig', [
            'livre' => $livre,
            'books' => $livre,

            'form' => $form->createView()

        ]);
    }





    /**
     * @Route("/showlivre", name="showlivre")
     */
    public function show()
    {
        $repository = $this->getDoctrine()->getRepository(Livre::class);
        $livre = $repository->findAll();

        return $this->render('livre/show.html.twig', [
            'b'=> $livre
        ]);
    }
    /**
     * @Route("/addlivre", name="add_livre" )
     */
    public function addlivre(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);

        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            $em = $this->getDoctrine()->getManager();

            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move($this->getParameter('images_directory'),$fileName);
            $livre->setImage($fileName);
            $em->persist($livre);
            $em->flush();
            $this->addFlash('info', 'Created Successfully !');
            return $this->redirectToRoute('showlivre');
        }

        return $this->render('livre/add.html.twig', [
            'livre' => $livre,
            'f' => $form->createView(),
        ]);
    }
    /**
     * @Route("/deletelivre/{id}", name="deletelivre")
     */
    public function delete(Request $request, Livre $livre): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($livre);
        $em->flush();

        return $this->redirectToRoute('showlivre', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/editlivre/{id}", name="editlivre")
     */
    public function edit(Request $request ,$id): Response
    {
        $livre= $this-> getDoctrine()->getManager()->getRepository(Livre::class)->find($id);
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('showlivre', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livre/edit.html.twig', [
            'livre' => $livre,
            'f' => $form->createView(),
        ]);
    }



    /**
     * @Route("/searsh", name="ajax_search")
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $posts =  $em->getRepository(Livre::class)->findEntitiesByString($requestString);
        if(!$posts) {
            $result['posts']['error'] = "Post Not found :( ";
        } else {
            $result['posts'] = $this->getRealEntities($posts);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($posts){
        foreach ($posts as $posts){
            $realEntities[$posts->getIdLivre()] = [$posts->getImage(),$posts->gettitre()];

        }
        return $realEntities;
    }

    /**
     * @Route("/searshx", name="searchStudentx")
     */
    public function searchStudentx(Request $request):jsonResponse
    {
        $repository = $this->getDoctrine()->getRepository(Livre::class);
        $livre = $repository->findAll();
        $livres= $this-> getDoctrine()->getManager()->getRepository(Livre::class)->findByDate();
        $requestString = $request->get('searchValue');
        $livre=$repository->createQueryBuilder('livre')
            ->where('livre.titre LIKE :nsc')
            ->setParameter('nsc', '%'.$requestString.'%')
            ->getQuery()
            ->getResult();
        //$utilisateurs = $repository->findBy(['username' => '%'.$requestString.'%']);
        dump($livre);
        //$jsonContent = $Normalizer->normalize($utilisateurs, 'json', ['groups' => 'students']);
        //$retour = json_encode($jsonContent);
        return new JsonResponse([
                'html' => $this->renderView('livre/index.html.twig', ['livre' => $livre,
                    'books' => $livre,
                    'b' => $livres])
            ]
        );
    }
    /**
     * @Route("/book/{id}", name="addbook")
     */
    public function showdetailedAction($id)
    {

        $em= $this->getDoctrine()->getManager();
        $p=$em->getRepository(Livre::class)->find($id);
        return $this->render('livre/book.html.twig', array(
            'titre'=>$p->getTitre(),
            'idLivre'=>$p->getIdLivre(),
            'image'=>$p->getImage(),
            'description'=>$p->getDescription()


        ));

    }
    /**
     * @Route("/pdf", name="pdf")
     */
    public function pdf()
    {
        // Configure Dompdf according to your needs
        $listpost=$this->getDoctrine()->getManager()->getRepository(Livre::class)->findAll();

        $pdfOptions= new Options();
        $pdfOptions->set('defaultFont','Arial');
        $dompdf= new Dompdf($pdfOptions);



        $html='Harry poter       :
    Il a les mêmes cheveux noirs
     en bataille que son père et les yeux vert émeraude
      et en forme damande de sa mère. Il porte des lunettes rondes à monture 
      noire et une fine cicatrice en forme déclair sur le front, souvent 
      cachée par ses mèches. Harry est assez timide et plutôt modeste.';
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4','portrait');
        $dompdf->render();
        $dompdf->stream('mypdf.pdf',[
            'Attachement' =>true
        ]);


    }

    /**
     * @Route("/stats", name="stats")
     */

    public function statistiques(PostRepository $livrepost){
        // On va chercher toutes les catégories
        $livre = $livrepost->findAll();

        $titre = [];
        $idLivre = [];
        $idCategorie = [];



        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($livre as $livres){
            $titre[] = $livres->getTitre();
            $idLivre[] = $livres->getIdLivre();
            if(is_countable($livres->getIdCategorie())) {
                $idCategorie[] = count($livres->getIdCategorie());
            }

        }

        return $this->render('stats.html.twig', [
            'titre' => json_encode($titre),
            'idLivre' => json_encode($idLivre),
            'idCategorie' => json_encode($idCategorie),


        ]);
    }
    public function date($max=4){
        $em = $this->getDoctrine()->getEntityManager();
        $livre = $em->getRepository('App:Livre')->getEntityFromDate(new \DateTime('now'), $max);
    }



    /**
     * @Route("/{idLivre}/ajouterFavoris", name="app_fav_add", methods={"GET","POST"})
     */
    public function ajouterFavoris(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('addFav' . $livre->getIdLivre(), $request->request->get('_token'))) {
            $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['username' => $this->getUser()->getUsername()]);
            $favoris = new Favoris();
            $favoris->setIdLivre($livre);
            $favoris->setIdUser($user);
            dump($favoris);
            $entityManager->persist($favoris);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{idLivre}/deleteFavoris", name="app_fav_delete", methods={"GET","POST"})
     */
    public function supprimerFavoris(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        dump($request->request->get('_token'));
        if ($this->isCsrfTokenValid('delFav' . $livre->getIdLivre(), $request->request->get('_token'))) {
            $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['username' => $this->getUser()->getUsername()]);
            $queryBuilder = $entityManager->createQueryBuilder();
            $query = $queryBuilder->select('f')
                ->from('App\Entity\Favoris', 'f')
                ->where('f.idUser = :c_user')
                ->andWhere('f.idLivre = :c_livre')
                ->setParameter('c_user', $user->getIdUser())
                ->setParameter('c_livre', $livre->getIdLivre())
                ->getQuery();
            $favoris = $query->getOneOrNullResult();

            dump($favoris);

            if ($favoris != null) {
                $entityManager->remove($favoris);
                $entityManager->flush();
            } else {
                $favoris = new Favoris();
                $favoris->setIdLivre($livre);
                $favoris->setIdUser($user);
                dump($favoris);
                $entityManager->persist($favoris);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
    }
}
