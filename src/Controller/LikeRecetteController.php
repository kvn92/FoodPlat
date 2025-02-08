<?php

namespace App\Controller;

use App\Entity\LikeRecette;
use App\Repository\LikeRecetteRepository;
use App\Service\StatusToggleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LikeRecetteController extends AbstractController
{
    #[Route('/like/recette', name: 'app_like_recette')]
    public function index(): Response
    {
        return $this->render('like_recette/index.html.twig', [
            'controller_name' => 'LikeRecetteController',
        ]);
    }

    #[Route('/{idPays}/toggle', name: 'toggle_statut', methods: ['GET'], requirements: ['idPays' => '\d+'])]
    public function statut(StatusToggleService $statusToggleService, LikeRecetteRepository $likeRecetteRepository, int $idLikeRecette): Response
    {
        $likeRecette = $likeRecetteRepository->find($idLikeRecette);
    
        if (!$likeRecette) {
            throw $this->createNotFoundException('Pays introuvable.');
        }
    
        $statusToggleService->toggleStatus($likeRecette, 'statutPays');
        $this->addFlash('success', 'bravo');
        return $this->redirectToRoute('recette.index');
    }
}
