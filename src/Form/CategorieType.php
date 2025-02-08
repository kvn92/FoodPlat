<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categorie',TextType::class,[
                'label'=>'Categorie','required'=>true,]);
          
          if($options['is_edit']){
            $builder ->add('statutCategorie',ChoiceType::class,['label'=>'Statut',
            'choices'=>[
                'Actif'=>true,'Pause'=>false
            ],
            'expanded'=>true,
            'multiple'=>false])
        
        ;
          }     
          $builder
          ->add('submit',SubmitType::class,[
            'label'=>'Enregistre'
          ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
            'is_edit'=>false,
            'csrf_protection' => true, // ✅ Active la protection CSRF
            'csrf_field_name' => '_token', 
            'csrf_token_id' => 'difficulte_item',
        ]);
    }
}
