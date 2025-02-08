<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('commentaire', name: 'commentaire.')]

class CommentaireController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }














    #[Route('/{id}/supprimer', name: 'supprimer', methods:['POST'])]
    public function supprimer(
        int $id,
        CommentaireRepository $commentaireRepository,
        EntityManagerInterface $entityManager,
        Security $security,
        CsrfTokenManagerInterface $csrfTokenManager,
        Request $request
    ): Response {
        // Récupérer le commentaire
        $commentaire = $commentaireRepository->find($id);
    
        if (!$commentaire) {
            throw $this->createNotFoundException('Commentaire introuvable.');
        }
    
        // Vérification de l'utilisateur
        $utilisateur = $security->getUser();
    
        if ($commentaire->getUtilisateur() !== $utilisateur) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à supprimer ce commentaire.');
            return $this->redirectToRoute('recette.show', ['id' => $commentaire->getRecette()->getId()]);
        }
    
        // Vérification du token CSRF
        $submittedToken = $request->request->get('_token');
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('delete_comment', $submittedToken))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }
    
        // Suppression
        $entityManager->remove($commentaire);
        $entityManager->flush();
    
        $this->addFlash('success', 'Commentaire supprimé avec succès.');
        return $this->redirectToRoute('recette.show', ['id' => $commentaire->getRecette()->getId()]);
    }
    

    
}
