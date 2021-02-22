<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{
    /**
     * @Route("/user", name="user_profil")
     */
    public function profilEdit(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository,UserPasswordEncoderInterface $encoder): Response
    {
        $userRepository=$this->getUser();
        $profilForm=$this->createForm(ProfilType::class, $userRepository);
        $profilForm->handleRequest($request);
        if($profilForm->isSubmitted() && $profilForm->isValid()){
            $hased= $encoder->encodePassword($userRepository, $userRepository->getPassword());
            $userRepository->setPassword($hased);
            $entityManager->persist($userRepository);
            $entityManager->flush();
            $this->addFlash("success", "modification enregistrée");
            return  $this->redirectToRoute('user_detail', ['id'=>$userRepository->getId()]);
        }
        return  $this->render('user/profilEdit.html.twig',[
            'profilForm'=>$profilForm->createView(),
            'user'=>$userRepository
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_detail", requirements={"id": "\d+"})
     */
    public function detail($id){
        $userrepository= $this->getDoctrine()->getRepository(User::class);
        $userdetail=$userrepository->find($id);
        return $this->render('user/detail.html.twig',[
            'userdetail'=>$userdetail
        ]);
    }
}
