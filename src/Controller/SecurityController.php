<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // ✅ Si l'utilisateur est déjà connecté, on vérifie son rôle pour rediriger
        if ($this->getUser()) {
            $user = $this->getUser();
    
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                return $this->redirectToRoute('pays.index'); // 🔹 Remplace par ta route admin
            }
    
            if (in_array('ROLE_MANAGER', $user->getRoles())) {
                return $this->redirectToRoute('viande.index'); // 🔹 Remplace par ta route manager
            }
    
            if (in_array('ROLE_USER', $user->getRoles())) {
                return $this->redirectToRoute('pays.index'); // 🔹 Remplace par ta route utilisateur
            }
    
            // 🔹 Si aucun rôle spécifique, redirection par défaut
            return $this->redirectToRoute('ingredient.index');
        }
    
        // Récupération de l'erreur d'authentification
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // Message d'erreur personnalisé
        $errorMessage = $error ? "Identifiants incorrects. Veuillez réessayer." : null;
    
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $errorMessage,
        ]);
    }
    

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
