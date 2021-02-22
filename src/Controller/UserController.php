<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\ResetPassType;
use App\Form\SortieType;
use App\Form\UserType;
use App\Form\VilleType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="user_register")
     */
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
    {

        $user= new User();
        $user->setAdministrateur(0);
        $user->setActif(0);
        $user->setRoles(["ROLE_USER"]);
        $registerForm= $this->createForm(UserType::class, $user);
        $registerForm->handleRequest($request);

        if($registerForm->isSubmitted() && $registerForm->isValid()){
            $hased= $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hased);
            $user->setActivationToken(md5(uniqid()));
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash("success", "Un mail vous a été envoyé cliquer sur le lien pour activer votre compte");
            $message = (new \Swift_Message('Activation compte'))
                ->setFrom('mon@adresse.fr')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                       'email/activation.html.twig',
                        ['token' => $user->getActivationToken()]
                    ),
                    'text/html'
                );

            $mailer->send($message);
            return  $this->redirectToRoute('home');
        }
        return $this->render('user/register.html.twig', [
            "registerform"=>$registerForm->createView()
        ]);
    }
    /**
     * @Route ("/login", name="login")
     */
    public function login(Request $request)
    {
        $session= $request->getSession();
        $session->start();
        return $this->render("user/login.html.twig", []);
    }

    /**
     * @Route ("/logout", name="logout")
     */
    public function logout(Request $request)
    {
        $session= $request->getSession();

        $session->invalidate();
        session_destroy();
       return $this->render("user/login.html.twig", []);

    }

    /**
     * @Route("/activation/{token}", name="activation")
     */
    public function activation($token, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $user = $userRepository->findOneBy(['activation_token'=>$token]);
        if(!$user){
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }
        $user->setActivationToken(null);
        $entityManager->persist($user);
        $entityManager->flush();
        $this->addFlash("success", "compte activé");
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/oublie_pass", name="forgotten_password")
     */
    public function forgottenPass(Request $request, UserRepository $userRepository,
                                  \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $entityManager): Response
    {
            $form=$this->createForm(ResetPassType::class);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $donnees=$form->getData();
                $user=$userRepository->findOneBySomeField($donnees["email"]);
                if(!$user){
                    $this->addFlash("danger", "cette adresse n'existe pas");
                    $this->redirectToRoute('login');
                }
                $token=$tokenGenerator->generateToken();
                try {
                    $user->setResetToken($token);
                    $entityManager->persist($user);
                    $entityManager->flush();
                }catch (\Exception $e)
                {
                    $this->addFlash("warning", "une erreur est survenu".$e->getMessage());
                    $this->redirectToRoute('login');
                }
                $url= $this->generateUrl('user_reset_password', ['token'=>$token] );
                $message = (new \Swift_Message('Mot de Passe oublié'))
                    ->setFrom('no-reply@nouvelle.fr')
                    ->setTo($user->getEmail())
                    ->setBody(
                        "<p>Bonjour une demande reinitialisation de mot de passe a été effectuée<p/>.
                                Veuillez cliquez sur le lien suivant: ".$url,
                        'text/html'
                    );

                $mailer->send($message);
                $this->addFlash("success", "Un email de réinitialisation de mot de passe vous a été envoyé");
                $this->redirectToRoute('login');
            }
            return $this->render('user/forgotten_pass.html.twig', ['emailForm'=>$form->createView()]);
    }

    /**
     * @Route("/reset-pass/{token}", name="user_reset_password")
     */
    public function resetpassword(Request $request, string $token, UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);
        if ($request->isMethod('POST')) {

            $user->setResetToken(null);

           // $hased= $encoder->encodePassword($user, $user->getPassword());
           // $user->setPassword($hased);
            $user->setPassword($encoder->encodePassword($user, $request->request->get("password")));

            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash('message', 'Mot de passe mis à jour');

            // On redirige vers la page de connexion
            return $this->redirectToRoute('login');
        }
        else
        {
            return $this->render('user/reset_password.html.twig', ['token' => $token]);
        }
    }

}
