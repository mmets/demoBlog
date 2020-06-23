<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{   // Permet d'injecter les fausses données fixtures
    // la méthode load() fait une injection de dépendance via 
    // l'interface ObjectManager et son objet $manager 

    public function load(ObjectManager $manager)
    {   // ObjectManager est une classe qui permet de 
        // manipuler, gérer les insertions en bdd

        // La classe ObjectManager est une classe prédéfinie en Symfony qui permet de manipuler les lignes de la BDD (INSERT, UPDATE, DELETE)


        // On va créer une boucle qui génère 10 articles 

        for ($i=1; $i <= 10 ; $i++) 
        { 
            // On instancie la classe Article qui cependant n'existe pas
            // Grâce à l'extension php namespace resolver, on va pouvoir 
            // importer les classes automatiquement du dossier entité/article.php
            // Pour importer les classes on peut cliquer sur la classe à importer 
            // puis faire Ctrl+Alt+i ou clic droit>import class



            $article = new Article();// Lorsqu'une classe n'a pas d'argument, on peut ommettre les ()

            $article->setTitle("Titre de l'article n° $i")
                    ->setText("Voici venu le jour n°$i")
                    ->setContent("<p>Contenu de l'article n°$i</p>")
                    ->setImage("https://picsum.photos/200")
                    ->setCreatedAt(new \DateTime());// l'antislash permet de revenir dans l'espace global le temps de la ligne
                    // car DateTime est une classe de php non définie dans ArticleFixtures 
                    // Il est possible d'utiliser use pour dire dans quel namespace on utilise DateTime

            $manager->persist($article);// prépare les requêtes d'insertion
            // persist() est une méthode issue de la classe ObjectManager qui permet de préparer les insertions et de les garder en mém


        }

        $manager->flush();// permet d'exécuter la requête d'insertion
    }
}
