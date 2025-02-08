<?php

namespace App\Form;

use App\Entity\TypeRepas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeRepasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('typeRepas',TextType::class,['label'=>'Type de Repas',
        'required'=>true,]);
       if($options['is_edit']){
        $builder->add('statutTypeRepas',ChoiceType::class,['label'=>'Statuts',
        'choices'=>[
            'Actif'=>true,
            'Pause'=>false,
        ],'expanded'=>true,
        'multiple'=>false
    ]);
       }
       

       $builder ->add('submit',SubmitType::class,[
            'label'=>'Enregistre']);
    ;
}

public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => TypeRepas::class,
        'is_edit'=>false,
        'csrf_protection' => false, // ✅ Active la protection CSRF
        'csrf_field_name' => '_token', 
        'csrf_token_id' => 'typeRepas_item',
    ]);
}
}
