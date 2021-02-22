<?php

namespace App\Controller;

use App\data\SearcheCampus;
use App\Entity\Campus;
use App\Entity\Ville;
use App\Form\CampusType;
use App\Form\SearchCampVilForm;
use App\Form\VilleType;
use App\Repository\CampusRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("", name="admin_ville")
     */
    public function ville(VilleRepository $villeRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        if(!$this->isGranted("ROLE_ADMIN"))
        {
            throw  new AccessDeniedException("Vous n'avez pas le droit");
        }
        $data = new SearcheCampus();
        $form=$this->createForm(SearchCampVilForm::class, $data);
        $form->handleRequest($request);
        $villes= $villeRepository->findVilles($data);

        return $this->render('admin/ville.html.twig', [
            'villes' => $villes,
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route("/ville", name="admin_ville_add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $ville= new Ville();
        $villeForm= $this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);
        if($villeForm->isSubmitted() && $villeForm->isValid())
        {

            $entityManager->persist($ville);

            $entityManager->flush();
            $this->addFlash("success", "Ajout réussi");
            return $this->redirectToRoute('admin_ville', []);
        }
        return  $this->render('admin/addville.html.twig', [
            'villeForm'=>$villeForm->createView()
        ] );
    }

    /**
     * @param $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @Route("/ville/updateVille/{id}", name="ville_update",  requirements={"id": "\d+"})
     * @return RedirectResponse|Response
     */
    public function updateVille($id, Request $request, EntityManagerInterface $entityManager, VilleRepository $repository)
    {
        $villerepo = $repository->find($id);
        $villeForm= $this->createForm(VilleType::class,$villerepo);
        $villeForm->handleRequest($request);
        if($villeForm->isSubmitted() && $villeForm->isValid())
        {
            $entityManager->persist($villerepo);
            $entityManager->flush();
            $this->addFlash("success", "modification réussie");

            return $this->redirectToRoute('admin_ville', []);
        }
        return $this->render('admin/updateville.html.twig',[

            'villeForm'=>$villeForm->createView()
        ]);


    }

    /**
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @Route("/deleteVille/{id}", name="ville_delete",  requirements={"id": "\d+"},methods={"GET"})
     */
    public function deleteVille($id, EntityManagerInterface $entityManager, VilleRepository $repository):Response
    {
        $villerepo = $repository->find($id);
        if(empty($villerepo)){
            throw $this->createNotFoundException("Ville inexistante");
        }
        $entityManager->remove($villerepo);
        $entityManager->flush();
        $this->addFlash('success', "The city has been deleted");
        return $this->redirectToRoute('admin_ville');

    }



    /**
     * @Route("/campus", name="admin_campus")
     */
    public function addcampus(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        $campus= new Campus();
        $campusForm= $this->createForm(CampusType::class, $campus);
        $campusForm->handleRequest($request);
        if($campusForm->isSubmitted() && $campusForm->isValid())
        {

            $entityManager->persist($campus);

            $entityManager->flush();
            $this->addFlash("success", "Ajout réussi");
            return $this->redirectToRoute('admin_campus_liste', []);
        }
        return $this->render('admin/campus.html.twig', [
            'campusForm'=>$campusForm->createView()
        ] );
         }


    /**
     * @Route("/campus/list", name="admin_campus_liste")
     */
    public function listcampus(CampusRepository  $campusRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        if(!$this->isGranted("ROLE_ADMIN"))
        {
            throw  new AccessDeniedException("Vous n'avez pas le droit");
        }
        $data = new SearcheCampus();
        $form=$this->createForm(SearchCampVilForm::class, $data);
        $form->handleRequest($request);
        $campus= $campusRepository->findCampus($data);

        return $this->render('admin/list_campus.html.twig', [
            'campus' => $campus,
            'form'=>$form->createView()
        ]);
    }
    /**
     * @param $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @Route("/campus/updateCampus/{id}", name="campus_update",  requirements={"id": "\d+"})
     * @return RedirectResponse|Response
     */
    public function updateCampus($id, Request $request, EntityManagerInterface $entityManager, CampusRepository $repository)
    {
        $campus = $repository->find($id);
        $campusForm= $this->createForm(CampusType::class,$campus);
        $campusForm->handleRequest($request);
        if($campusForm->isSubmitted() && $campusForm->isValid())
        {
            $entityManager->persist($campus);
            $entityManager->flush();
            $this->addFlash("success", "modification réussie");

            return $this->redirectToRoute('admin_campus_liste', []);
        }
        return $this->render('admin/updatecampus.html.twig',[

            'campusForm'=>$campusForm->createView()
        ]);


    }

    /**
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @Route("/deleteCampus/{id}", name="campus_delete",  requirements={"id": "\d+"},methods={"GET"})
     */
    public function deleteCampus($id, EntityManagerInterface $entityManager, CampusRepository $repository):Response
    {
        $campus = $repository->find($id);
        if(empty($campus)){
            throw $this->createNotFoundException("campus inexistante");
        }
        $entityManager->remove($campus);
        $entityManager->flush();
        $this->addFlash('success', "The campus has been deleted");
        return $this->redirectToRoute('admin_campus_liste');

    }

}
