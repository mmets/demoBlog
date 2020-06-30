<?php

namespace App\Controller;

use DateTime;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Article;// Bien vérifier que la classe sur laquelle on fera les manip 
                       // est appellée


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

    // On crée une nouvelle route / qui correspond à une nouvelle page Home

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

    // Route pour la page Créer un article
    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    // Lorsqu'on définit une nouvelle route qui contient un id, on doit 
    // injecter une dépendance Article et initialiser son objet à null pour 
    // pouvoir récupérer les données de l'article en base et les modifier
    public function form(Article $article = null, Request $request, EntityManagerInterface $manager)
    {
        /*  
            La classe Request est une classe prédéfinie de Symfony qui récupère 
            les données du formulaire et les stocke dans les superglobales 
            ($_POST, $_COOKIE, $_SERVER, ..etc) 
            On a accès à ces données via l'objet $request qui est une instance 
            de la classe Request

            EntityManagerInterface est une interface prédéfinie de Symfony 
            qui permet d'insérer les données en BDD.
            Elle contient les méthodes permettant de préparer et exécuter 
            les requêtes SQL (persist() et flush())
        */
        //dump($request);//permet d'avoir un ensemble d'infos dont celles entrées 
                        // dans le formulaire

        /*
            $request->request est une propriété de $request qui permet d'avoir 
            accès aux données du formulaire; c'est un $_POST
        */

        // Avant d'insérer les données dans la BDD, on vérifie que le formulaire n'est pas vide 

        /*if($request->request->count() > 0)
        {
            /* On crée un objet article dans lequel les données du formulaire seront 
                stockées pour être insérées en base
            */
            // $article = new Article;

            // $article->setTitle($request->request->get('title'))
            //         ->setContent($request->request->get('content'))
            //         ->setImage($request->request->get('image'))
            //         ->setCreatedAt(new DateTime);

            /*
                persist() est une méthode issue de EntityManagerInterface permettant
                de préparer et sticker la requête SQL

                flush() permet quant à elle de libérer et d'exéuter ladite requête
            */
            // $manager->persist($article);
            // $manager->flush();

            // dump($article);

            /*
                Une fois que l'article est créé, on le redirige vers sa page dédiée.
                Pour le faire, on utilise la méthode 
                redirectToRoute('name de la route', ['id'=>$id])
            */

        //     return $this->redirectToRoute('blog_show', [
        //             'id' => $article->getId()
        //     ]);

        // }

        
        /*
            createFormBuilder($article) est une méthode de Symfony qui permet de créer 
            un formulaire dont les paramètres permettront de remplir l'objet $article
            
            add() permet de créer les champs du formulaire

            getForm() permet de valider l'apparence du formulaire

            createView() permet de créer une vue càd un objet qui contient le formulaire 
            afin de l'envoyer à view(create.html.view). Elle est indispensable pour pouvoir 
            afficher le formulaire
        */
        // test pour remplir le formulaire
        // $article->setTitle("Titre random")
        //         ->setContent('Random content'); 
        

        /* $form = $this->createFormBuilder($article)

                   // ->add('title')
                    //  ->add('title', TextType::class, [
                    //      'attr' => [
                    //          'placeholder' => "Titre de votre article",
                    //          'class' => "form-control"
                    //      ]
                    //  ])

                    On peut préciser les paramètres de 'title' en rajoutant une classe 
                     TextType
                     
                     //->add('content')
                    // ->add('image')

                    //  ->add('submit', SubmitType::class)

                    //->getForm();
        */

        /*
            Si l'article n'existe pas (n'a pas encore été crée); $article == null, 
            aucun ID n'est existant en base et n'est transmis à l'URL. Nous sommes donc 
            dans le cas d'une insertion. 
            Il faut alors instancier un objet pour stocker les données issues du formulaire
            On entre dans la condition uniquement lorsqu'on insère un nouvel article
        */
        if(!$article)
        {
            $article = new Article;
        }    
        
        /* 
            Autre manière de créer un formulaire    
            Il est possible de créer un formulaire à partir d'une entité article 
            en utilisant php bin/console make:form

        */

        $form = $this->createForm(ArticleType::class, $article);
        /* createForm() : méthode Symfony qui permet de créer un formulaire. 
            On importe la classe ArticleType qui permet de créer le formulaire
    
         Elle attend en argument la classe du formulaire et l'objet 
         de l'entité Article qui va réceptionner les données du formulaire
        */
        /* handleRequest() permet de récupérer toutes les valeurs du formulaire 
        contenues dans $request ($_POST) afin de les envoyer directement dans les 
        setter de l'objet $article
        */ 
        $form->handleRequest($request);
        
        // Si le formulaire a bien été soumis (qu'on a cliqué sur 'submit et tout est bien validé), 
        // càd que chaque valeur du formulaire a bien été transmise au bon setter, alors on rentre
        // dans la condition

        /* 
            Sécuriser le formulaire
        */
        if($form->isSubmitted() && $form->isValid())
        {
            /* Pour conserver la date d'origine de création de l'article en cas de modification, 
                on contrôle l'existence de l'id de l'article 
                S'il est inexistant, on rentre bien dans la condition et une nouvelle date est créée 
            */
            if(!$article->getId())
            {
                $article->setCreatedAt(new \DateTime);
            }

            $manager->persist($article);
            $manager->flush();

            dump($article);

            return $this->redirectToRoute('blog_show', [
                            'id' => $article->getId()
            ]);
        }

        return $this->render('blog/create.html.twig', [
            'form_article' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
       
    }
    
    // show(): méthode permettant de voir le détail des articles

    /**
     * @Route("/blog/{id}", name="blog_show")
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
           [ 'article' => $article ,
           ]);
    }

    /* Pour éviter les conflits, on évite de déclarer de nouvelles routes 
        après avoir déclarer une route où on attend un paramètre comme 
        celui-ci "/blog/{id}"
        Si on crée la route "/blog/new" à la suite de la route "/blog/{id}" , 
        Symfony identifiera new comme un paramètre {id}

    */


     

}

