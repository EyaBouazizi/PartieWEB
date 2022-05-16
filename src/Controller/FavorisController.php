<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Entity\Utilisateur;
use App\Form\FavorisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/favoris")
 */
class FavorisController extends AbstractController
{
    /**
     * @Route("/", name="app_favoris_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
            $favoris = $this->getDoctrine()->getRepository(Favoris::class)->findAll();
            dump($favoris);

            return $this->render('favoris/index.html.twig', [
                'favoris' => $favoris,
            ]);


        } elseif (in_array('ROLE_USER', $this->getUser()->getRoles(), true)) {

            $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['username' => $this->getUser()->getUsername()]);

            $query = $this->getDoctrine()->getRepository(Favoris::class)->createQueryBuilder('f')
                ->select('IDENTITY(f.idLivre)')->where('f.idUser = :c_user')
                ->setParameter('c_user', $user->getIdUser())->getQuery();

            $favoris = $query->getResult();
            dump($favoris);

            $queryBuilder = $entityManager->createQueryBuilder();
            $query = $queryBuilder->select('f')
                ->from('App\Entity\Livre', 'f')
                ->where('f.idLivre IN (:ids)')
                ->setParameter('ids', $favoris)
                ->getQuery();
            $livre = $query->getArrayResult();
        }
        return $this->render('livre/index.html.twig', [
            'b' => $livre,
            'books' => [],
        ]);
    }

    /**
     * @Route("/new", name="app_favoris_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $favori = new Favoris();
        $form = $this->createForm(FavorisType::class, $favori);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($favori);
            $entityManager->flush();

            return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('favoris/new.html.twig', [
            'favori' => $favori,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idFav}", name="app_favoris_show", methods={"GET"})
     */
    public function show(Favoris $favori): Response
    {
        return $this->render('favoris/show.html.twig', [
            'favori' => $favori,
        ]);
    }

    /**
     * @Route("/{idFav}/edit", name="app_favoris_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Favoris $favori, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FavorisType::class, $favori);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('favoris/edit.html.twig', [
            'favori' => $favori,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idFav}", name="app_favoris_delete", methods={"POST"})
     */
    public function delete(Request $request, Favoris $favori, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $favori->getIdFav(), $request->request->get('_token'))) {
            $entityManager->remove($favori);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
    }


}
