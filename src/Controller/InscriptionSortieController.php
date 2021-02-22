<?php


namespace App\Controller;
use App\Entity\Sortie;
use App\Form\InscritSortieType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionSortieController extends AbstractController
{

    /**
     * @Route("/inscription/{id}", name="inscription-regist",requirements={"id": "\d+"})
     */
    public function inscriptionSortie($id, EntityManagerInterface $entityManager, Request $request)
    {
        if (null === $sortie = $entityManager->getRepository(Sortie::class)->find($id)) {
            throw $this->createNotFoundException('Aucune sortie trouvée pour cet identifiant ' . $id);
        }


        $connecteduser = $this->getUser();
        /*$originalUsers = new ArrayCollection();
        foreach ($sortie->getUsers() as $connecteduser) {
            $originalUsers->add($connecteduser);
        }*/

        if ($request->isMethod('POST')) {
            if ($sortie->getUsers()->contains($connecteduser)) {
                throw $this->createNotFoundException('Vous êtes déja inscrit(e)');

            }

            $sortie->addUsers($connecteduser);

            /*foreach ($originalUsers as $connecteduser) {
                if (false === $sortie->getUsers()->contains($connecteduser)) {

                    $connecteduser->removeUser($sortie);
                    $entityManager->persist($connecteduser);

                }
            }*/

            $entityManager->persist($sortie);
            $entityManager->flush();
            return $this->redirectToRoute('sortie_detail', ['id' => $id]);

        } else {
            return $this->render('sortie/inscrituser.html.twig', [
                "sortie" => $sortie,
                'user' => $connecteduser]);
        }
    }


    /**
     * @Route("/desinscrire/{id}", name="desinscrire-regist",requirements={"id": "\d+"})
     */
    public function desinscrire($id, EntityManagerInterface $entityManager, Request $request)
    {
        if (null === $sortie = $entityManager->getRepository(Sortie::class)->find($id)) {
            throw $this->createNotFoundException('Aucune sortie trouvée pour cet identifiant ' . $id);
        }
        $connecteduser = $this->getUser();
        if ($request->isMethod('POST')) {
            if ($sortie->getUsers()->contains($connecteduser)) {

                $sortie->removeUser($connecteduser);
            }
            $entityManager->persist($sortie);
            $entityManager->flush();
            return $this->redirectToRoute('sortie_detail', ['id' => $id]);

        }
        else {
            return $this->render('sortie/desinscrire.html.twig', [
                "sortie" => $sortie,
                'user' => $connecteduser]);
        }

    }
}