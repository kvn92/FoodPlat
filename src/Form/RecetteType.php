<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Difficulte;
use App\Entity\Ingredient;
use App\Entity\Pays;
use App\Entity\Recette;
use App\Entity\TypeRepas;
use App\Entity\Viande;
use App\Enum\CategorieEnum;
use App\Enum\DifficulterEnum;
use App\Enum\IngredientEnum;
use App\Enum\PaysEnum;
use App\Enum\RepasEnum;
use App\Enum\ViandeEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreRecette',TextType::class,['label'=>'Titre'])

 
 

            ->add('photoRecette',FileType::class,[
                'required'=>false,
                'mapped'=>false,'constraints'=>[new Image(
                    
                )]])

            ->add('duree',IntegerType::class,[
                'label'=>'Durée',
                'required'=>true])
            ->add('submit',SubmitType::class,['label'=>'Ajoute'])

            ->add('pays', ChoiceType::class, [
                'choices' => PaysEnum::cases(),
                'choice_label' => fn(PaysEnum $choice) => $choice->name, // Affichage du nom
                'choice_value' => fn(?PaysEnum $choice) => $choice?->value, // Stocke la valeur de l'ENUM
                'expanded' => false,
                'multiple' => false,
                'label' => 'Pays d’origine',
                'data' => $options['data']->getPays()?->value, // Définit la valeur par défaut
            ])

            ->add('categorie', ChoiceType::class, [
                'choices' => CategorieEnum::cases(),
                'choice_label' => fn(CategorieEnum $choice) => $choice->name, // Affichage du nom
                'choice_value' => fn(?CategorieEnum $choice) => $choice?->value, // Stocke la valeur de l'ENUM
                'expanded' => false,
                'multiple' => false,
                'label' => 'Categorie',
                'data' => $options['data']->getCategorie()?->value, // Définit la valeur par défaut
            ])
            ->add('difficulte', ChoiceType::class, [
                'choices' => DifficulterEnum::cases(),
                'choice_label' => fn(DifficulterEnum $choice) => $choice->name, // Affichage du nom
                'choice_value' => fn(?DifficulterEnum $choice) => $choice?->value, // Stocke la valeur de l'ENUM
                'expanded' => false,
                'multiple' => false,
                'label' => 'Niveau',
                'data' => $options['data']->getDifficulte(), // Définit la valeur par défaut
            ])
            ->add('ingredient', ChoiceType::class, [
                'choices' => IngredientEnum::cases(),
                'choice_label' => fn(IngredientEnum $choice) => $choice->name, // Affichage du nom
                'choice_value' => fn(?IngredientEnum $choice) => $choice?->value, // Stocke la valeur de l'ENUM
                'expanded' => false,
                'multiple' => false,
                'label' => 'Ingredient',
                'data' => $options['data']->getIngredient()?->value, // Définit la valeur par défaut
            ])
            ->add('repas', ChoiceType::class, [
                'choices' => RepasEnum::cases(),
                'choice_label' => fn(RepasEnum $choice) => $choice->name, // Affichage du nom
                'choice_value' => fn(?RepasEnum $choice) => $choice?->value, // Stocke la valeur de l'ENUM
                'expanded' => false,
                'multiple' => false,
                'label' => 'Repas',
                'data' => $options['data']->getRepas()?->value, // Définit la valeur par défaut
            ])
            ->add('Viande', ChoiceType::class, [
                'choices' => ViandeEnum::cases(),
                'choice_label' => fn(ViandeEnum $choice) => $choice->name, // Affichage du nom
                'choice_value' => fn(?ViandeEnum $choice) => $choice?->value, // Stocke la valeur de l'ENUM
                'expanded' => false,
                'multiple' => false,
                'label' => 'Viande',
                'data' => $options['data']->getViande()?->value, // Définit la valeur par défaut
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
            'csrf_protection' => true, // ✅ Active la protection CSRF
        'csrf_field_name' => '_token', 
        'csrf_token_id' => 'recette_item',
        ]);
    }
}
