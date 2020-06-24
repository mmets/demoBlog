<?php

namespace App\Controller;

use App\Entity\Article;// Bien vérifier que la classe sur laquelle on fera les manip 
                       // est appellée
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticleRepository;

// Toutes les méthodes seront déclarées à l'intérieur de la 
// classe BlogController extends AbstractController

// Toutes les méthodes qui ont un niveau de visibilité (public, private)
// doivent être déclarées dans une classe

class BlogController extends AbstractController
{
    // Symfony fonctionne toujours avec un système de routage
    // transmis dans l'url

    // Le code qui suit est interprété par Symfony. Il doit être 
    // laissé tel quel avec les 4 * 

    
    // INJECTION DE DEPENDANCES
    /*
        index() dépend de la classe ArticleRepository, on poura lui envoyer en 
        paramètre la classe ArticleRepository avec son objet $repo
        Lorsqu'on injecte une indépendance index(ArticleRepository $repo), 
        on n'a plus besoin de $repo = $this->getDoctrine()->getRepository(Article::class);

        Il ne faut pas oublier d'importer la classe ArticleRepository comme on l'a fait 
        pour Article avec use App\Entity\Article

    */

    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
        /* 
            Pour sélectionner les données en BDD, on a besoin de la classe Repository 
            de la classe Article
            Cette classe permet uniquement de sélectionner les données en BDD (requête SQL SELECT)
            On a aussi besoin de l'ORM Doctrine pour établir la relation entre la BDD et le 
            Controller (avec getDoctrine()) 
            getRepository() est une méthode issue d l'objet Doctrine qui permet d'importer une 
            classe Repository (SELECT)

            $repo est un objet issu de la classe ArticleRepository, cette classe contient des 
            méthodes prédéfinies par SYMFONY (find(), findAll(), findOneBy(), findBy()) 
            permettant de sélectionner des données en BDD 

            findAll() est une méthode issue de la classe ArticleRepository permettant de sélectionner 
            l'ensemble de la table SQL, dans notre cas, la table Artcile

        */

        // On importe la classe Article pour pouvoir effectuer la requête $repo
        // $repo permet d'avoir accès à toutes les méthodes de ArticleRepository 
        // comme si on fait un fetchAll ou fetch
        // $repo = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repo->findAll();// <=> (SELECT * FROM Article )->fetchAll

        dump($articles);// <=> var_dump ou print_r

        // Lorsqu'on envoie la route /blog dans l'url (localhost:8000/blog)
        // La méthode index() sert à exécuter render qui renvoie un rendu 
        // Il va dans templates/blog/index
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles, // On envoie sur le template 'index.html.twig' 
            // les articles sélectionnées en BDD ($articles) que nous allons traiter 
            // avec le langage TWIG
        ]);
    }

    // On crée une nouvelle route qui correspond à une nouvette page

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

    /**
     * @Route("/blog/new", name="blog_create")
     */
    public function create()
    {
        return $this->render('blog/create.html.twig');
    }
    
    // show(): méthode permettant de voir le détail des articles

    /**
     * @Route("/blog/{id}", name="blog_show", requirements={"page"="{id}"})
     */

    // Pour envoyer un id dans l'url, on indique le paramètre {id}
    // Ce paramètre est attendu en argument de la méthode show()

    public function show(ArticleRepository $repo, $id)
    {
        /*
            Pour sélectionner 1 article en BDD, càd voire le détail d'1 article, 
            on utilise le principe de route paramétrée ("/blog/{id}")
            Cette route attend un paramètre de type {id}, donc l'id d'un article qui 
            est stocké en BDD 
            Lorsqu'une route est transmise en URL (comme blog/2), Symfony récupère 
            automatiquement le paramètre id=2 et l'envoie en argument de la méthode show($id)

            On a ainsi accès à l'{id} à l'intérieur de la méthode show()
            Le but est de selectionner les données en BDD de l'{id} récupéré en paramètre
            Nous avons besoin pour cela de la classe ArticleRepository afin de pouvoir 
            selectionner en BDD

            La méthode find() issue de la classe ArticleRepository permet de selectionner 
            des données en BDD avec un argument de type {id}

            DOCTRINE fait ensuite tout le travail pour nous, c'est à dire qu'elle recupère 
            la requete de selection pour l'ex

        */

        // $repo = $this->getDoctrine()->getRepository(Article::class);
        
        $article = $repo->find($id);
        
        dump($article);

        return $this->render('blog/show.html.twig', 
           [ 'article' => $article
           ]);
    }

    /* Pour éviter les conflits, on évite de déclarer de nouvelles routes 
        après avoir déclarer une route où on attend un paramètre comme 
        celui-ci "/blog/{id}"
        Si on crée la route "/blog/new" à la suite de la route "/blog/{id}" , 
        Symfony identifiera new comme un paramètre {id}

    */


     

}

