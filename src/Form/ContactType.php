<?php
declare(strict_types=1);

namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Votre nom',
                'empty_data' => '',
                'attr' => ['placeholder' => 'Entrez votre nom', 'class' => 'form-control'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'empty_data' => '',
                'attr' => ['placeholder' => 'Entrez votre email', 'class' => 'form-control'],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'empty_data' => '',
                'attr' => ['placeholder' => 'Écrivez votre message...', 'class' => 'form-control', 'rows' => 5],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => ['class' => 'btn btn-primary w-100 mt-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }
}
