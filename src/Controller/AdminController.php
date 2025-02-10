<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Service\DeleteService;
use App\Service\StatusToggleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('admin', name: 'admin.')]
final class AdminController extends AbstractController{


    #[Route('', name: 'tableauBord', methods: ['GET'])]
    public function test(){

        return $this->render('admin/tab_admin.html.twig');
    }


    #[Route('/list', name: 'index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository, DeleteService $deleteService ): Response
    {
        $titre = 'Gestion des membres';
        $admins = $utilisateurRepository->findAll();
        $nombre = $utilisateurRepository->count();
        $deleteForms = [];
    
        foreach ($admins as $admin) { 
            $deleteForms[$admin->getId()] = $deleteService
                ->createDeleteForm($admin, 'delete_user')
                ->createView();
    }

    return $this->render('admin/list_membre.html.twig', [
        'admins' => $admins, // Changer le nom pour correspondre à Twig
        'titre' => $titre,
        'secteur' => 'user',
        'nombre' => $nombre,
        'deleteForms' => $deleteForms,
        'sdsd'=>'ddd'
    ]);
    }

    #[Route('/new', name: 'new', methods: ['POST'])]



    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id, UtilisateurRepository $utilisateurRepository): Response{

        $utilisateur = $utilisateurRepository->find($id);
        if(!$utilisateur){
            return $this->redirectToRoute('admin.index');
        }

        $recettes = $utilisateur->getRecettes();
        $favoris = $utilisateur->getFavoris();
        $likes = $utilisateur->getLikeRecettes();
        $follows = $utilisateur->getFollowing();
        $suivre = $utilisateur->getFollowers();

        return $this->render('admin/profil.html.twig',[
            'utilisateur'=>$utilisateur,
            'recettes'=>$recettes,
            'nombresFavoris' =>count($favoris),
            'nombres'=> count($recettes),
            'nombresLikes'=>count($likes),
            'suit'=>count($follows),
            'suivi'=>count($suivre)


        ]
            );
    }


    #[Route('/{id}/edit', name: 'edit', methods: ['POST'])]


    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Utilisateur $utilisateur, Request $request, DeleteService $deleteService): Response
    {
        $wordEntity = 'admin';
        $redirect = $deleteService->handleDelete($utilisateur, $request, 'delete_'.$wordEntity, $wordEntity.'.index');
    
        if ($redirect) {
            $this->addFlash('success', ' supprimée avec succès.');
            return $this->redirect($redirect);
        }
    
        $this->addFlash('error', 'Échec de la suppression.');
        return $this->redirectToRoute($wordEntity.'.index');
    }


    #[Route('/{id}/toggle-statut', name: 'toggle_statut', methods: ['GET'])]
    public function toggleStatus(Utilisateur $utilisateur, StatusToggleService $statusToggleService): Response
    {
        // Basculer le statut
        $statusToggleService->toggleStatus($utilisateur, 'statutUtilisateur');

        // Rediriger vers la indexe des catégories
        return $this->redirectToRoute('admin.index');
    }
}
