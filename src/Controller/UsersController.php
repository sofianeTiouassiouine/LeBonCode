<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersController extends AbstractController
{
    /**
     * @Route("/api/register", name="user", methods={"POST"})
     */
    public function Save(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator,  UserPasswordEncoderInterface $encoder): Response
    {
        $objectValues = $request->getContent(); 
        try {
            $utilisateur = $serializer->deserialize($objectValues, Utilisateur::class, 'json');

            $utilisateur->setPassword($encoder->encodePassword($utilisateur, $utilisateur->getPassword()));
            $errors = $validator->validate($utilisateur);
            if(count($errors) > 0){
                return $this->json($errors, 400);
            }
            $em->persist($utilisateur);
            $em->flush();
            return $this->json($utilisateur, 201, [], ['groups' => 'utilisateur:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    
    }
    
    /**
     * @Route(name="api_login", path="/api/login", methods={"POST"} )
     * @return JsonResponse
     */
    public function api_login(): JsonResponse
    {
        $user = $this->getUser();
        return new JsonResponse([
            'email' => $user.getMail(),
            'roles' =>[],
        ]);
    }
}
