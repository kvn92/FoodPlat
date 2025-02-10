<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/membre', name: 'membre.')]
final class MembreController extends AbstractController{
   
    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('membre/index.html.twig', [
            'controller_name' => 'MembreController',
        ]);
    }

    #[Route('/{id}', name: 'show', methods:['GET'])]
    public function show(UtilisateurRepository $utilisateurRepository, int $id): Response
    {
        $utilisateur = $utilisateurRepository->find($id);
        $recettes = $utilisateur->getRecettes();
        $favoris = $utilisateur->getFavoris();
        $likes = $utilisateur->getLikeRecettes();
        $follows = $utilisateur->getFollowing();
        $suivre = $utilisateur->getFollowers();

        return $this->render('membre/membre.html.twig', [
         'utilisateur'=>$utilisateur,
            'recettes'=>$recettes,
            'nombresFavoris' =>count($favoris),
            'nombres'=> count($recettes),
            'suit'=>count($follows),
            'suivi'=>count($suivre)
       
       
        ]);
    }
}
