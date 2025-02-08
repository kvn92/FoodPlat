<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Champ de texte pour le nom de l'ingrédient
        $builder->add('nomIngredient', TextType::class, [
            'label' => 'Ingrédient',
            'required' => true,
        ]);

        // Ajout du champ statut uniquement en mode édition
        if ($options['is_edit']) {
            $builder->add('statutIngredient', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Actif' => true,
                    'Inactif' => false,
                ],
                'multiple' => false,
                'expanded' => true, // Correction : `expand` remplacé par `expanded`
            ]);
        }

        // Bouton de soumission avec libellé dynamique
        $builder->add('submit', SubmitType::class, [
            'label' => $options['is_edit'] ? 'Modifier' : 'Enregistrer',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
            'is_edit' => false, // Option par défaut pour indiquer si on est en mode édition
            'csrf_protection' => true, // ✅ Active la protection CSRF
            'csrf_field_name' => '_token', 
            'csrf_token_id' => 'dingredient_item',
        ]);
    }
}
