<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

// Toutes les méthodes seront déclarées à l'intérieur de la 
// classe BlogController extends AbstractController

// Toutes les méthodes qui ont un niveau de visibilité (public, private)
// doivent être déclarées dans une classe

class BlogController extends AbstractController
{
    // Sympfony fonctionne toujours avec un système de routage
    // transmis dans l'url

    // Le code qui suit est interprété par Symfony. Il doit être 
    // laissé tel quel avec les 4 * 

    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        // Lorsqu'on envoie la route /blog dans l'url (localhost:8000/blog)
        // La méthode index() sert à exécuter render qui renvoie un rendu 
        // Il va dans templates/blog/index
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    // On crée une nouvelle route

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        // La méthode render peut aussi renvoyer des données issues 
        // d'une BDD 
        return $this->render('blog/home.html.twig', 
        ['title'=>'Histoires racontées', 
        'age'=> 1
        ]);
        // On va ensuite créer le fichier home.html.twig dans templates
    }

}

