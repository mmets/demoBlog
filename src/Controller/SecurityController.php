<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function login()
    {
        return $this->render("security/login.html.twig");
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
