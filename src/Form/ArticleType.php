<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*
            On ajoute un champ qui permettra de sélectionner une catégorie
            Comme category ne fait pas partie de la table article, on doit 
            préciser d'où vient le champ provient.
            On précise d'abord que le champ provient d'une entité, ensuite 
            on indique que l'entité en question est Category.
            Pour finir, comme dans la table category on a plusieurs champs, 
            on doit indiquer quel champ on choisit d'afficher

            Comme on fait appel à de nouvelles classes, on s'assure de les importer
        */
        $builder
            ->add('title')
            ->add('content')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'titre'
            ])
            ->add('image')
            // ->add('createdAt') Comme le champ date est généré automatiquement, 
            // on n'a pas besoin d'ajouter le temps
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
