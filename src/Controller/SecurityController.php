<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'login')]
    #[Route(path: '/login', name: 'login')]
public function login(AuthenticationUtils $authenticationUtils): Response
{
    // Récupération de l'erreur d'authentification
    $error = $authenticationUtils->getLastAuthenticationError();
    
    // ✅ Convertir l'erreur en message personnalisé
    $errorMessage = $error ? "Identifiants incorrects. Veuillez réessayer." : null;

    return $this->render('security/login.html.twig', [
        'last_username' => $authenticationUtils->getLastUsername(),
        'error' => $errorMessage, // ✅ On passe une chaîne et non un objet
    ]);
}

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
