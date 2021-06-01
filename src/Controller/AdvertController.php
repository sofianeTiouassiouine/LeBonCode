<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface; 


class AdvertController extends AbstractController
{
    /**
     * @Route("/api/advert", name="api_advert_getall", methods={"GET"})
     */
    public function getall(AnnonceRepository $annonceRepo): Response
    {
        $annonces = $annonceRepo->findAll();
        return $this->json($annonces, 200, [], ['groups' => 'annonce:read']);
    }

    /**
     * @Route("/api/advert/{id}", name="api_advert_getid", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function getById(int $id, AnnonceRepository $annonceRepo): Response
    {
        $annonce = $annonceRepo->find($id);
        return $this->json($annonce, 200, [], ['groups' => 'annonce:read']);
    }

    /**
     * @Route("/api/advert", name="api_advert_post", methods={"POST"})
     */
    public function Save(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        $objectValues = $request->getContent(); 
 
        try {
            $annonce = $serializer->deserialize($objectValues, Annonce::class, 'json');
            $errors = $validator->validate($annonce);
            if(count($errors) > 0){
                return $this->json($errors, 400);
            }
            $em->persist($annonce);
            $em->flush();
            return $this->json($annonce, 201, [], ['groups' => 'annonce:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    
    }

    /**
     * @Route("/api/advert/search", name="api_advert_search", methods={"GET"})
     */
    public function search(Request $request, AnnonceRepository $annonceRepo ): Response
    {
        $titre =$request->query->get('titre');
        $min =$request->query->get('min');
        $max =$request->query->get('max');
        $annonces =  $annonceRepo->findByTitleAndPrice($titre, $min, $max);
        return $this->json($annonces, 200, [], ['groups' => 'annonce:read']);
    }

    /**
     * @Route("/api/advert/{id}", name="api_advert_delete", methods={"DELETE"})
     */
    public function delete(int $id, AnnonceRepository $annonceRepo, EntityManagerInterface $em): Response
    {
        $annonce = $annonceRepo->find($id);

        if ($annonce != null){
            $em->remove($annonce);
            $em->flush();
            return $this->json($annonce, 200, [], ['groups' => 'annonce:read']);
        }

        return $this->json([
            'status' => 404,
            'message' => "Annonce n'existe pas!"
        ], 404);
    }

    /**
     * @Route("/api/advert/{id}", name="api_advert_update", methods={"PATCH"})
     */
    public function update(int $id, Request $request, AnnonceRepository $annonceRepo, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        $objectValues = $request->getContent(); 
        $origineAnnonce = $annonceRepo->find($id);
        if ($origineAnnonce != null){
            try {
                $annonce = $serializer->deserialize($objectValues, Annonce::class, 'json');
                
                $errors = $validator->validate($annonce);
                if(count($errors) > 0){
                    return $this->json($errors, 400);
                }

                $origineAnnonce->setDescription( $annonce->getDescription());
                $origineAnnonce->setVille( $annonce->getVille());
                $origineAnnonce->setCodePostal( $annonce->getCodePostal());
                $origineAnnonce->setPrix( $annonce->getPrix());

                $em->persist($origineAnnonce);
                $em->flush();

                return $this->json($origineAnnonce, 201, [], ['groups' => 'annonce:read']);
            } catch (NotEncodableValueException $e) {
                return $this->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ], 400);
            }
        } 

        return $this->json([
                    'status' => 404,
                    'message' => "Annonce n'existe pas!"
                ], 404);
    }
}
