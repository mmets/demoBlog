<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{   // Permet d'injecter les fausses données fixtures
    // la méthode load() fait une injection de dépendance via 
    // l'interface ObjectManager et son objet $manager 

    public function load(ObjectManager $manager)
    {   
        /* La libriaire FAKER permet d'injecter de fausses données 
        plus spécifiques comme des paragraphes ou des noms
        */
        $faker = \Faker\Factory::create('fr_FR') ;

        // Création de 3 catégories
        for ($i=1; $i < 3; $i++) 
        { 
            /*
                On instancie la classe Category afin de lui affecter des 
                valeurs issues de la librairie FAKER via les setter 

                $manager->persist($category) prépare les données pour 
                une insertion en BDD
            */
            $category = new Category;
    
            $category->setTitre($faker->sentence())
                      ->setDescription($faker->paragraph());

            $manager->persist($category);

            // Création de 4 à 6 articles par catégorie
            for ($j=1; $j <= mt_rand(4,6) ; $j++) 
            { 
                /* 
                    On instancie ensuite l'entité Article afin d'insérer 
                    des données en BDD via les différents setter

                    Dans le cas de setContent, on lui définit une variable 
                    $content qui va stocker les paragraphes fictifs concatenés

                    Pour ce qui est de la date, on définit un intervalle de 
                    telle sorte que la date de création de l'article ne puisse 
                    pas être dans le futur

                    setCategory($category) permet de relier les articles aux 
                    catégories correspondantes
                */
                $article = new Article;

                $content = '<p>'. join($faker->paragraphs(5),'</p><p>') .'</p>';

                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setText($faker->sentence())
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);

                $manager->persist($article);

                // Création de 4 à 10 articles par catégorie
                for ($k=1; $k <= mt_rand(4,10) ; $k++) 
                { 
                    /*
                        On instancie l'entité Comment afin d'insérer des commentaires 
                        Ici, on s'assure que la date de création des commentaires ne 
                        peut pas être antérieure à la date de création de l'article
                    */
                    $comment = new Comment;

                    $content = '<p>'. join($faker->paragraphs(5),'</p><p>') .'</p>';

                    $now = new \Datetime;
                    $interval = $now->diff($article->getCreatedAt()); // représente la
                    // différence de temps entre la date de créattion de l'article et 
                    // maintenant ($now)
                    $days = $interval->days; // nombre de jours entre la date de créa de 
                    // l'article et maintenant
                    $minimum = '-'. $days .'days';
                    $comment->setAuthor($faker->name())
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);

                    $manager->persist($comment);
                        
                }
            }
        }
        $manager->flush();

    }
}