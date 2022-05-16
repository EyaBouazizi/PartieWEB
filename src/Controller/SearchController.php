<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SearchController extends AbstractController
{
    /**
     * @Route("/searchStudentx ", name="searchStudentx")
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function searchStudentx(Request $request, NormalizerInterface $Normalizer): Response
    {
        $repository = $this->getDoctrine()->getRepository(Utilisateur::class);
        $requestString = $request->get('searchValue');
        $utilisateurs = $repository->createQueryBuilder('utilisateur')
            ->where('utilisateur.username LIKE :nsc')
            ->setParameter('nsc', '%' . $requestString . '%')
            ->getQuery()
            ->getResult();
        //$utilisateurs = $repository->findBy(['username' => '%'.$requestString.'%']);
        dump($utilisateurs);
        //$jsonContent = $Normalizer->normalize($utilisateurs, 'json', ['groups' => 'students']);
        //$retour = json_encode($jsonContent);
        return new JsonResponse([
            "html" => $this->renderView('utilisateur/u_table.html.twig', ['utilisateurs' => $utilisateurs]),
        ]);

    }
}