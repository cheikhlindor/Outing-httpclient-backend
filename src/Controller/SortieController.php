<?php

namespace App\Controller;

use App\data\SearcheData;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\SearchForm;
use App\Form\SortieType;
use App\Form\VilleType;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SortieController extends AbstractController
{

    /**
     * @Route("/sortie", name="sortie_creersortie")
     */
    public function creersortie(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        if(!$this->isGranted("ROLE_ADMIN"))
        {
            throw  new AccessDeniedException("Vous n'avez pas le droit");
        }
        $userRepository=$this->getUser();
        $sortie= new Sortie();
        $lieu=new Lieu();
        $ville= new Ville();


        $villeForm= $this->createForm(VilleType::class,$ville);
        $lieuForm=$this->createForm(LieuType::class, $lieu);
        $sortieForm=$this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);
        $lieuForm->handleRequest($request);
        $villeForm->handleRequest($request);
        if($sortieForm->isSubmitted() && $sortieForm->isValid() && $lieuForm->isSubmitted()
            && $lieuForm->isValid() && $villeForm->isSubmitted() && $villeForm->isValid())
        {

            $entityManager->persist($ville);
            $entityManager->flush();
            $lieu->setVille($ville);

            $entityManager->persist($lieu);
            $entityManager->flush();
            $sortie->setLieu($lieu);
            $sortie->setEtat("x");
            $sortie->setUser($userRepository);


            $entityManager->persist($sortie);

            $entityManager->flush();

            $this->addFlash("success", "Ajout réussi");

            // return $this->redirectToRoute('user_detail_sortie', ['id'=>$sortie->getId()]);
            return $this->redirectToRoute('sortie_list', [ ]);
        }

        return $this->render('sortie/creerSortie.html.twig',[
            'sortieForm'=>$sortieForm->createView(),
            'lieuForm'=>$lieuForm->createView(),
            'villeForm'=>$villeForm->createView()
        ]);
    }

    /**
     * @Route("/sortie/list", name="sortie_list")
     */
    public function list(Request $request)
    {
        $data= new SearcheData();
        $form=$this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        $userRepository=$this->getUser();
        $sortieRepo= $this->getDoctrine()->getRepository(Sortie::class);
        $sorties=$sortieRepo->findRecentSortie($data);

        /*$client= HttpClient::create();
        $url= 'http://localhost/sortir/public/api/sortie';
        $reponse = $client->request('GET', $url);
        $sorties = $reponse->toArray();*/

        return $this->render('sortie/list.html.twig', [
            "sorties"=>$sorties,
            "userRepository" => $userRepository,
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/sortie/search", name="sortie_search")

    public function search(SortieRepository $sortieRepository)
    {
        $data= new SearcheData();
        $form=$this->createForm(SearchForm::class, $data);
        $sorties = $sortieRepository->findSearch();

        return $this->render('sortie/search.html.twig', [
            "sorties"=>$sorties,
            'form'=>$form->createView()
        ]);
    }*/

    /**
     * @Route("/sortie/{id}", name="sortie_detail",  requirements={"id": "\d+"}, methods={"GET"})
     */
    public function detailSortie($id)
    {
        $sortieRepo=$this->getDoctrine()->getRepository(Sortie::class);
        $sortie=$sortieRepo->find($id);
        return $this->render('sortie/detailSortie.html.twig', [
            "sortie"=>$sortie
        ]);

    }

    /**
     * @Route("/sortie/cancel/{id}", name="sortie_cancel",  requirements={"id": "\d+"})
     */
    public function cancel($id, Request $request, EntityManagerInterface $entityManager)
    {
        $sortieRepo=$this->getDoctrine()->getRepository(Sortie::class);
        $sortie=$sortieRepo->find($id);
        if($request->isMethod('POST'))
        {
            $sortie->setIsactive(0);
            $sortie->setMotif($request->request->get("motif"));
            $entityManager->persist($sortie);

            $entityManager->flush();

            return $this->redirectToRoute('sortie_list');
        }
        else
        {
            return  $this->render('sortie/cancel.html.twig', [
                "sortie"=>$sortie]);
        }
    }


    /**
     * @param $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @Route("/sortie/updatesortie/{id}", name="sortie_update",  requirements={"id": "\d+"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function updatesortie($id, Request $request, EntityManagerInterface $entityManager)
    {
        $sortieRepo=$this->getDoctrine()->getRepository(Sortie::class);

        $sortie=$sortieRepo->find($id);
        $userRepository=$this->getUser();
        $lieu=$sortie->getLieu();
        $ville= $lieu->getVille();
        $updateFrom=$this->createForm(SortieType::class,$sortie);
        $villeForm= $this->createForm(VilleType::class,$ville);
        $lieuForm=$this->createForm(LieuType::class, $lieu);
        $updateFrom->handleRequest($request);
        $lieuForm->handleRequest($request);
        $villeForm->handleRequest($request);
        if($updateFrom->isSubmitted() && $updateFrom->isValid() && $lieuForm->isSubmitted()
            && $lieuForm->isValid() && $villeForm->isSubmitted() && $villeForm->isValid()) {

            $entityManager->persist($ville);
            $entityManager->flush();
            $lieu->setVille($ville);

            $entityManager->persist($lieu);
            $entityManager->flush();
            $sortie->setLieu($lieu);
            $sortie->setUser($userRepository);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash("success", "modification réussie");

           return $this->redirectToRoute('sortie_list', []);
        }
        return $this->render('sortie/updateSortie.html.twig',[
            'updateFrom'=>$updateFrom->createView(),
            'lieuForm'=>$lieuForm->createView(),
            'villeForm'=>$villeForm->createView()
        ]);


    }


}
