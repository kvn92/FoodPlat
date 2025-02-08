<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{

    #[Route('/membre', name: 'membre')]
    public function membre(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $user->setRoles(['ROLE_ADMIN']);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('recette.index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
    
    #[Route('/register', name: 'register')]
public function Admistr(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
{
    $user = new Utilisateur();
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);
    $user->setRoles(['ROLE_ADMIN']);

    $error = null; // Initialiser la variable error

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupération du mot de passe
        $plainPassword = $form->get('plainPassword')->getData();

        // Encodage du mot de passe
        $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

        $entityManager->persist($user);
        $entityManager->flush();

        // Redirection après inscription réussie
        return $this->redirectToRoute('recette.index');
    } elseif ($form->isSubmitted()) {
        $error = "Le formulaire contient des erreurs. Veuillez vérifier vos informations.";
    }

    return $this->render('registration/register.html.twig', [
        'registrationForm' => $form->createView(),
        'error' => $error, // On passe l'erreur à Twig
    ]);
}

}
