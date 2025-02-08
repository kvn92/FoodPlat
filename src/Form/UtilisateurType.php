<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

use function PHPSTORM_META\map;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['is_edit']) {
            // Formulaire pour l'édition
            $this->buildEditForm($builder);
        } else {
            // Formulaire par défaut (ajout)
            $this->buildDefaultForm($builder);
        }
    }

    private function buildDefaultForm(FormBuilderInterface $builder): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'required' => true,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Modérateur' => 'ROLE_MODERATEUR',
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Rôles',
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer',
            ]);
    }

    private function buildEditForm(FormBuilderInterface $builder): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'required' => true,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Modérateur' => 'ROLE_MODERATEUR',
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Rôles',
            ])

            ->add('photo',FileType::class,[
                'constraints'=>[
                new File(
                    
                    maxSize:'100000M'
                    ,maxSizeMessage:'Le dossier est trop lourd'
                    
)]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            'is_edit' => false, // Détermine si le formulaire est pour l'édition
        ]);
    }
}
