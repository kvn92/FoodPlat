<?php

namespace App\Form;

use App\Entity\Pays;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaysType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomPays',TextType::class,[
                'label'=>'Pays',
                'required'=>true]);
           if($options['is_edit']){
            $builder
            ->add('statutPays',ChoiceType::class,[
                'label'=>'statu','choices'=>
                [
                    'Actif'=>true,
                    'Inactif'=>false
                ],
                'multiple'=>false,
                'expanded'=>true]);
           }
        ;
        $builder
        ->add('submit',SubmitType::class,[
            'label'=>$options['is_edit']?'Modifier':'Enregistre']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pays::class,
            'is_edit'=>false,
            'csrf_protection' => true, // ✅ Active la protection CSRF
            'csrf_field_name' => '_token', 
            'csrf_token_id' => 'idPays_item',
        ]);
    }
}
