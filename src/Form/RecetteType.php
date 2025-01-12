<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Difficulte;
use App\Entity\Recette;
use App\Entity\TypeRepas;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'placeholder'=>'Choisir',
                'choice_label' => 'categorie',
            ])
            ->add('difficulte',EntityType::class,[
                'class'=> Difficulte::class,
                'placeholder'=>'Choisir',
                'choice_label'=>'difficulte',
            ])
            ->add('typeRepas',EntityType::class,[
                'class'=>TypeRepas::class,
                'placeholder'=>'choisir',
                'choice_label'=>'typeRepas'
                ])
            ->add('photoRecette',FileType::class,[
                'required'=>true,
                'mapped'=>false,'constraints'=>[new Image(
                    
                )]])

            ->add('duree',IntegerType::class,[
                'label'=>'Durée',
                'required'=>true])
            ->add('submit',SubmitType::class,['label'=>'Ajoute'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
