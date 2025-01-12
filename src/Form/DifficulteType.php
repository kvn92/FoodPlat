<?php

namespace App\Form;

use App\Entity\Difficulte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DifficulteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('difficulte',TextType::class,['label'=>'Difficulte',
            'required'=>true,])
            ->add('statutDifficulte',ChoiceType::class,['label'=>'Statuts',
                'choices'=>[
                    'Actif'=>true,
                    'Pause'=>false,
                ],'expanded'=>true,
                'multiple'=>false
                ])

            ->add('submit',SubmitType::class,[
                'label'=>$options['boutonForm']?
                'Ajouter':'Modifier'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Difficulte::class,
            'boutonForm'=>true
        ]);
    }
}
