<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\LikeRecette;
use App\Entity\Recette;
use App\Entity\Utilisateur;
use App\Form\CommentaireType;
use App\Form\RecetteType;
use App\Repository\CommentaireRepository;
use App\Repository\LikeRecetteRepository;
use App\Repository\RecetteRepository;
use App\Repository\UtilisateurRepository;
use App\Service\DeleteService;
use App\Service\StatusToggleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/recettes', name: 'recette.')]
class RecetteController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(RecetteRepository $recetteRepository, DeleteService $deleteService): Response
    {
        $titre = "Index des Recettes";
        $recettes = $recetteRepository->findAll();
        $nombres = $recetteRepository->countRecette();
        $deleteForms = [];
    
        // ✅ Correction : S'assurer que le `foreach` ne bloque pas le `return`
        foreach ($recettes as $v) { 
            $deleteForms[$v->getId()] = $deleteService
                ->createDeleteForm($v, 'delete_utilisateurs')
                ->createView();
        }
    
        // ✅ Le `return` est maintenant toujours atteint
        return $this->render('recette/index.html.twig', [
            'titre' => $titre,
            'recettes' => $recettes,
            'nombres' => $nombres,
            'secteur'=>'recettes',
            'deleteForms' => $deleteForms,
        ]);
    }
    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recette);
            $entityManager->flush();

            /** @var UploadedFile $file */
            $file = $form->get('photoRecette')->getData();
            if ($file) {
                $fileName = $recette->getId() . '.' . $file->guessExtension();
                $destination = $this->getParameter('kernel.project_dir') . '/public/recettes/images';
                $file->move($destination, $fileName);

                $recette->setPhotoRecette($fileName);
                $entityManager->flush();
            }

            $this->addFlash('success', 'Recette ajoutée avec succès !');
            return $this->redirectToRoute('recette.index');
        }

        return $this->render('recette/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET','POST' ], requirements: ['id' => '\d+'])]
    public function show(
        Request $request,
        RecetteRepository $recetteRepository,
        CommentaireRepository $commentaireRepository,
        Security $security,
        EntityManagerInterface $entityManager,
        int $id
    ): Response {
        $recette = $recetteRepository->find($id);
        $user = $security->getUser();

        if (!$recette) {
            throw $this->createNotFoundException('Recette introuvable.');
        }

        $commentaires = $commentaireRepository->findBy(['recette' => $recette],['dateCommentaire'=>'DESC']);
        $nbCommentaire = $commentaireRepository->count(['recette' => $recette]);
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        
        $commentaire->setRecette($recette)->setUtilisateur($user);

        
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($commentaire);
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire ajouté avec succès.');

            return $this->redirectToRoute('recette.show', ['id' => $id]);
        }

        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'form' => $form->createView(),
            'commentaires' => $commentaires,
            'nbCommentaire'=> $nbCommentaire,
        
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Recette $recette, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Recette modifiée avec succès.');
            return $this->redirectToRoute('recette.index');
        }

        return $this->render('recette/edit.html.twig', [
            'form' => $form->createView(),
            'titre' => 'Modifier la Recette',
        ]);
    }

    #[Route('/{id}/toggle', name: 'toggle_statut', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function toggleStatus(StatusToggleService $statusToggleService, Recette $recette): Response
    {
        $statusToggleService->toggleStatus($recette, 'statutRecette');
        $this->addFlash('success', 'Statut mis à jour avec succès.');
        return $this->redirectToRoute('recette.index');
    }

    #[Route('/{id}/like', name: 'like', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function like(Recette $recette, EntityManagerInterface $entityManager): Response
    {
        $recette->setNbLike($recette->getNbLike() + 1);
        $entityManager->flush();

        $this->addFlash('success', 'Vous aimez cette recette !');
        return $this->redirectToRoute('recette.show', ['id' => $recette->getId()]);
    }

    #[Route('/{id}/dislike', name: 'dislike', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function dislike(Recette $recette, EntityManagerInterface $entityManager): Response
    {
        $recette->setNbLike($recette->getNbLike() - 1);
        $entityManager->flush();

        $this->addFlash('success', 'Vous n’aimez plus cette recette.');
        return $this->redirectToRoute('recette.show', ['id' => $recette->getId()]);
    }


    #[Route('/{id}/like', name: 'recette_like', methods: ['POST'])]
public function toggleLike(
    int $id, 
    RecetteRepository $recetteRepository, 
    LikeRecetteRepository $likeRecetteRepository, 
    EntityManagerInterface $entityManager,
    Security $security
): Response {
    $recette = $recetteRepository->find($id);
    $user = $security->getUser();

    if (!$recette) {
        throw $this->createNotFoundException('Recette introuvable.');
    }

    if (!$user) {
        $this->addFlash('error', 'Vous devez être connecté pour liker une recette.');
        return $this->redirectToRoute('recette.show', ['id' => $id]);
    }

    // Vérifier si l'utilisateur a déjà liké cette recette
    $like = $likeRecetteRepository->findOneBy(['utilisateur' => $user, 'recette' => $recette]);

    if ($like) {
        // Supprimer le like
        $entityManager->remove($like);
        $this->addFlash('success', 'Like retiré.');
    } else {
        // Ajouter un like
        $like = new LikeRecette();
        $like->setUtilisateur($user);
        $like->setRecette($recette);
        $like->setBooleanLike(true);
        $entityManager->persist($like);
        $this->addFlash('success', 'Recette likée.');
    }

    $entityManager->flush();

    return $this->redirectToRoute('recette.show', ['id' => $id]);
}

#[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
public function delete(Recette $recette, Request $request, DeleteService $deleteService): Response
{
    $wordEntity = 'recette';
    $redirect = $deleteService->handleDelete($recette, $request, 'delete_'.$wordEntity, $wordEntity.'.index');

    if ($redirect) {
        $this->addFlash('success', ' supprimée avec succès.');
        return $this->redirect($redirect);
    }

    $this->addFlash('error', 'Échec de la suppression.');
    return $this->redirectToRoute($wordEntity.'.index');
}
}
