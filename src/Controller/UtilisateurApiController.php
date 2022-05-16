<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/utilisateur/api", name="app_utilisateur_api")
 */
class UtilisateurApiController extends AbstractController
{

    /**
     * @Route("/signin", name="applogin")
     */

    public function signinAction(Request $request, NormalizerInterface $normalizer): Response
    {
        $username = $request->query->get("username");
        $password = $request->query->get("password");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Utilisateur::class)->findOneBy(['username' => $username]);//bch nlawj ala user b username ta3o fi base s'il existe njibo
        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                $formatted = $normalizer->normalize($user);
                return new Response(json_encode($formatted));
            } else {
                return new Response("Wrong Password");
            }
        } else {
            return new Response("Wrong Entries");

        }
    }

    /**
     * @Route("/displayUsers", name="display_utilisateur")
     * @throws ExceptionInterface
     */

    public function allUsers(NormalizerInterface $normalizer): Response
    {

        $user = $this->getDoctrine()->getManager()->getRepository(Utilisateur::class)->findAll();
        $formatted = $normalizer->normalize($user);
        return new Response(json_encode($formatted));

    }

    /**
     * @Route("/deleteUser", name="delete_utilisateur")
     * @throws ExceptionInterface
     */

    public function deleteReclamationAction(Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Utilisateur::class)->find($id);
        if ($user != null) {
            $em->remove($user);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Utilisateur a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id Utilisateur invalide.");


    }

    /**
     * @Route("/updateUser", name="update_user")
     */
    public function updateUser(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getManager()
            ->getRepository(Utilisateur::class)
            ->find($request->get("id"));

        $nomprenom = $request->query->get("nomPrenom");
        $birthday = $request->query->get("age");
        $type = $request->query->get("type");


        $user->setNomPrenom($nomprenom);
        $user->setAge(new \DateTime(strtotime($birthday)));
        $user->setType($type);

        $em->persist($user);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse("1");

    }

    /**
     * @Route("/addUser", name="add_User")
     * @Method("POST")
     */

    public function addUser(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new Utilisateur();
        $username = $request->query->get("username");
        $password = $request->query->get("password");
        $email = $request->query->get("email");
        $nomprenom = $request->query->get("nomPrenom");
        $birthday = $request->query->get("age");
        $type = $request->query->get("type");

        $em = $this->getDoctrine()->getManager();

        $user->setUsername($username);
        $encoded = $passwordEncoder->encodePassword($user, $password);
        $user->setPassword($encoded);
        $user->setEmail($email);
        $user->setNomPrenom($nomprenom);
        $user->setAge(new \DateTime(strtotime($birthday)));
        $user->setType($type);

        $em->persist($user);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/findUserByEmail", name="Email_User")
     * @Method("POST")
     */

    public function findUserByEmail(Request $request, NormalizerInterface $normalizer): Response
    {
        $email = $request->query->get("email");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);
        if ($user) {
            $formatted = $normalizer->normalize($user);
            return new Response(json_encode($formatted));
        } else {
            return new Response("Email is non existant!");

        }
    }

    /**
     * @Route("/updatePassword", name="update_password")
     * @Method("POST")
     */

    public function updatePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getManager()
            ->getRepository(Utilisateur::class)
            ->find($request->get("id"));

        $password = $request->query->get("password");

        $user->setPassword($password);
        $encoded = $passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encoded);

        $em->persist($user);
        $em->flush();

        if ($user)
            return new JsonResponse("1");
        else
            return new JsonResponse("0");
    }


}
