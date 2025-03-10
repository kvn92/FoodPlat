<?php

namespace App\Form;

use App\Entity\Commentaire;
use App\Entity\Recette;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentaire',TextareaType::class,['required'=>false])
         
         
            ->add('submit',SubmitType::class,['label'=>'Commentaire']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
            'csrf_protection' => false, // ✅ Active la protection CSRF
            'csrf_field_name' => '_token', 
            'csrf_token_id' => 'commentaire_item',
        ]);
    }
}
