<?php


namespace App\Controller\Api;


use App\data\SearcheData;
use App\Entity\Sortie;
use App\Repository\SortieRepository;

use Doctrine\ORM\EntityManagerInterface;
use PhpParser\JsonDecoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * @Route("/api")
 */

class ApiSortieController extends AbstractController
{
    /**
     * Liste des sorties
     * @Route("/sortie", name="liste", methods={"GET"})
     */
    public function liste(SortieRepository $repository, SerializerInterface $serializer)
    {
        $data= new SearcheData();
        $sorties=$repository->findRecentSortie($data);

        $json = $serializer->serialize(
               $sorties,
               'json', ['groups' => 'liste_sorties'] );


        //, Request $request=>get content (request)-> desialize (la classe à récupérer)

        return new Response($json, Response::HTTP_OK, ["Content-type"=>"application/json"]);

    }

    /**
     * @Route("/sortie/{id}", name="lire",   methods={"GET"})
      */
    public function detailSortie($id, SerializerInterface $serializer)
    {
        $sortieRepo=$this->getDoctrine()->getRepository(Sortie::class);
       //crrer un methode qui récupère ceux dont on a besoin
        $sortie=$sortieRepo->find($id);
        $json = $serializer->serialize($sortie, 'json', ['circular_reference_handler' => function($object){
            return $object->getId();
        }]);
        if (empty($sortie)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        return new Response($json, Response::HTTP_OK, ["Content-type"=>"application/json"]);
    }

    /**
     * @Route("/sortie/ajout", name="ajout", methods={"POST"})
     */
    public  function addSortie(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $v)
    {
        try {

            $donnees =$request->getContent();
            $sortie = $serializer->deserialize($donnees, Sortie::class, 'json');
            $error = $v ->validate($sortie);
            if(count($error) >0){
                return  $this->json($error, 400);
            }
            $em -> persist($sortie);
            $em -> flush();
            return  $this->json($sortie, 201, [], ['groups' => 'liste_sorties']);
            //return new Response('ok', 201);
        }catch (NotEncodableValueException $e){
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * @Route("/sortie/editer/{id}", name="editer", methods={"PUT"})
     */
    public function editSortie(?Sortie $sortie, Request  $request)
    {
        if($request->isXmlHttpTRequest()){
            $donnees = json_decode($request->getContent());
            $code = 200;
            if(!$sortie) {
                $sortie = new Sortie();
                $code = 201;
            }

            $sortie ->setNom($donnees->nom);
            $userRepository=$this->getUser();
            $sortie->setUser($userRepository);
            $em  = $this->getDoctrine()->getManager();
            $em -> persist($sortie);
            $em -> flush();
            return new Response('ok', $code);
        }
        return  new Response('Erreur', 404);

    }

    /**
     * @Route("/sortie/supprimer/{id}", name="delete", methods={"DELETE"})
     * @param Sortie $sortie
     * @return Response
     */
    public function remove(Sortie $sortie)
    {
        $em  = $this->getDoctrine()->getManager();
        $em->remove($sortie);
        $em -> flush();
        return new Response('ok');
    }
}