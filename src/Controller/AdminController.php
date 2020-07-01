<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/admin_articles", name="admin_articles")
     */
    public function adminArticles(ArticleRepository $repo)
    {
        /*
            Il existe une méthode getManager() qui est le gestionnaire 
            d'entités de Doctrine. 
            Elle permet d'enregistrer des objets et de récupérer des données liées à la table (schéma) mais pas les 
            données en elles-même

            getClassMetaData() permet de récolter les métadonnées d'une table SQL 
            (nom des champs, classes, clés primaires, type de champs ), à partir 
            d'une entité / class

            getFieldNames() permet de récupérer le nom des champs (colonnes d'une table)
        */
        $em = $this->getDoctrine()->getManager();

        $colonnes = $em->getClassMetaData(Article::class)->getFieldNames();

        $articles = $repo->findAll();

        dump($articles);
        dump($colonnes);

        return $this->render('admin/admin_articles.html.twig', [
            'articles' => $articles,
            'colonne' => $colonnes
        ]);
    }

    /**
     * @Route("/admin/{id}/edit_article", name="admin_edit_article")
     */
    public function editArticle(Article $article)
    {
        dump($article);

        $form = $this->createForm(ArticleType::class, $article);
        
        return $this->render('admin/edit_article.html.twig', [
            'formEdit' => $form->createView()
        ]);
    }
    
}
