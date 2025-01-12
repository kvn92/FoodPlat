<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Recette;
use App\Form\CommentaireType;
use App\Form\RecetteType;
use App\Repository\CommentaireRepository;
use App\Repository\RecetteRepository;
use App\Service\StatusToggleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/recette', name: 'recette.')]
class RecetteController extends AbstractController
{
    #[Route('', name: 'list')]
    public function index(RecetteRepository $recetteRepository): Response
    {
        $titre = "liste Recette";
        $recettes = $recetteRepository->findAll();
        $nombreRecette = $recetteRepository->countRecette();
        return $this->render('recette/index.html.twig', [
            'titre'=>$titre,
            'recettes' => $recettes,
            'nombreRecette'=>$nombreRecette
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
    
        $errors = $validator->validate($recette);
    
        if ($form->isSubmitted() && $form->isValid() && count($errors) === 0) {
            // Persister et flush pour obtenir un ID généré
            $entityManager->persist($recette);
            $entityManager->flush();
    
            /** @var UploadedFile $file */
            $file = $form->get('photoRecette')->getData();
            if ($file) {
                // Vérifier l'extension et normaliser le nom
                $fileName = $recette->getId() . '.' . $file->guessExtension();
    
                // Déplacer le fichier vers le répertoire spécifié
                $destination = $this->getParameter('kernel.project_dir') . '/public/recettes/images';
                $file->move($destination, $fileName);
    
                // Mettre à jour le nom du fichier dans l'entité
                $recette->setPhotoRecette($fileName);
    
                // Sauvegarder à nouveau pour mettre à jour le fichier
                $entityManager->flush();
            }
    
            $this->addFlash('success', 'Recette ajoutée avec succès !');
            return $this->redirectToRoute('recette.list');
        }
    
        return $this->render('recette/new.html.twig', [
            'form' => $form,
            'errors' => $errors,
        ]);
    }
    

    #[Route('/{id}', name: 'show', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function show(
        Request $request,
        RecetteRepository $recetteRepository,
        CommentaireRepository $commentaireRepository,
        EntityManagerInterface $entityManager,
        int $id
    ): Response {
        // Récupérer la recette par son ID
        $recette = $recetteRepository->find($id);
    
        if (!$recette) {
            throw $this->createNotFoundException('Recette introuvable.');
        }
    
        // Récupérer tous les commentaires liés à cette recette
        $commentaires = $commentaireRepository->findBy(['recette' => $recette]);
    
        // Créer un nouveau commentaire
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
    
        // Lier le commentaire à la recette
        $commentaire->setRecette($recette);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire ajouté avec succès.');
    
            // Rediriger pour éviter la resoumission du formulaire
            return $this->redirectToRoute('recette.show', ['id' => $id]);
        }
    
        // Afficher la recette et ses commentaires
        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'form' => $form->createView(),
            'commentaires' => $commentaires,
        ]);
    }
    

  

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
public function edit(Request $request, EntityManagerInterface $entityManager, Recette $recette, ValidatorInterface $validator): Response
{
    $form = $this->createForm(RecetteType::class, $recette);
    $form->handleRequest($request);
    if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) { 
        $entityManager->persist($recette); // Facultatif si l'entité est déjà gérée
        $entityManager->flush();
        $this->addFlash('Success', 'Modifié avec succès');
        return $this->redirectToRoute('recette.list');
    }

    return $this->render('recette/edit.html.twig', [
        'form' => $form,
        'titre' => 'Modifier',
    ]);
}


#[Route('/{id}/toggle', name: 'toggle_statut',methods:['GET'],requirements:['id'=>'\d+'])]

public function statut(StatusToggleService $statusToggleService, Recette $recette){
    $statusToggleService->toggleStatus($recette, 'statutRecette');
    $this->addFlash('success','bravo');
    return $this->redirectToRoute('recette.list');
}

#[Route('/{id}/like', name: 'like', methods: ['POST'], requirements: ['id' => '\d+'])]
public function like(Recette $recette, EntityManagerInterface $entityManager): Response
{
    // Incrémenter le nombre de likes
    $recette->setNbLike($recette->getNbLike() + 1);

    // Sauvegarder les changements dans la base de données
    $entityManager->flush();

    // Rediriger vers la page de la recette
    return $this->redirectToRoute('recette.show', ['id' => $recette->getId()]);
}

#[Route('/{id}/dislike', name: 'dislike', methods: ['POST'], requirements: ['id' => '\d+'])]
public function dislike(Recette $recette, EntityManagerInterface $entityManager, ): Response
{
 // Incrémenter le nombre de likes
    $recette->setNbLike($recette->getNbLike() - 1);

    // Sauvegarder les changements dans la base de données
    $entityManager->flush();

    // Rediriger vers la page de la recette
    return $this->redirectToRoute('recette.show', ['id' => $recette->getId()]);
}


}
