<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        /*
            On instancie la classe Use qui va stocker les données récupérées par 
            le formulaire via l'objet $request
         */
        $user = new User;

        $form = $this->createForm(RegistrationType::class, $user);

        dump($request);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            /*
                Pour encoder le mdp, on définit un objet $hash qui va réceptionner 
                le mdp via $user->getPassword, le mdp récupéré du formulaire
                On définit ensuite ce mot de passe crypté comme nouveau mot de passe
                pour ensuite l'envoyer en BDD

            */
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            // On attribue le ROLE_USER à chaque nouvel utilisateur inscrit sur le blog
            // Il pourra créer et modifier des articles mais il n'aura pas accès à la 
            // partie backOffice
            $user->setRoles(["ROLE_USER"]);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Félicitations! Vous êtes bien inscrits.');

            return $this->redirectToRoute('security_login');

        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
            // 'login' => $user->getPassword()
        ]);

    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils) :Response
    {
        /*
            On injecte la dépendance AuthenticationUtils.
            La classe AuthenticationUtils contient des méthodes qui 
            affichent des messages d'erreur, le dernier username(email) 
            inscrit dans le formulaire

            $error renvoie le message d'erreur en cas de mauvaise connexion
            si l'internaute a saisi des indentifants erronés au moment
            de la connexion

            $lastUsername permet de récupérer le dernier username (email) que 
            l'internaute a inscrit lors de la connexion

            Après avoir défini les variables $error et $lastUsername, on 
            les envoie dans la vue(template) grâce à return 
        */

        $error =$authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("security/login.html.twig", [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
        // Cette méthode ne retourne rien. Il nous suffit d'avoir 
        // une route pour la déconnexion
    }
}
