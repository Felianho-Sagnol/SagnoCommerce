<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * montre la page d'inscription
     * @Route("/user", name="user_inscription")
     */
    public function index(Request $request, ManagerRegistry $managerRegistry,UserPasswordEncoderInterface $encoder)
    {
        $manager = $managerRegistry->getManager();

        $user = new User();
        $form = $this->createForm(InscriptionType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $password = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($password);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user_login');
        }


        return $this->render('user/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * fonction permettant de se connecter sur le site
     * @Route("/login", name="user_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        dump($error);
        $username = $utils->getLastUsername();

        return $this->render('user/login.html.twig',[
            "hasError" => $error !== null,
            "username" => $username
        ]);
    }

    /**
     * Undocumented function
     *@Route("/logout",name="user_logout")
     * @return void
     */
    public function logout(){
        //
    }

    /**
     * permet d'afficher le profile d'un utilisateur
     * @Route("/user/{slug}/profile",name="user_profile")
     * @return void
     */
    public function profile(User $user,$slug) {
        return $this->render('user/profile.html.twig',[
           'user' =>  $user
        ]);
    }
}
