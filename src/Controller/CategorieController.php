<?php

namespace App\Controller;

use App\Service\StatusToggleService;
use App\Entity\Categorie;
use App\Entity\Utilisateur;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Service\DeleteService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/categorie', name: 'categorie.')]

class CategorieController extends AbstractController
{
    
    #[Route('', name: 'index', methods: ['GET'])]
public function index(CategorieRepository $categorieRepository, DeleteService $deleteService): Response 
{
    $titre = 'Index des catégories';
    $categories = $categorieRepository->findAll();
    $nombre = $categorieRepository->count();
    $deleteForms = [];

    foreach ($categories as $categorie) { 
        $deleteForms[$categorie->getId()] = $deleteService
            ->createDeleteForm($categorie, 'delete_categorie')
            ->createView();
    }

    return $this->render('categorie/index.html.twig', [
        'categories' => $categories, // Changer le nom pour correspondre à Twig
        'titre' => $titre,
        'secteur' => 'Catégorie',
        'nombre' => $nombre,
        'deleteForms' => $deleteForms,
    ]);
}


    #[Route('/new', name: 'new',methods:['POST','GET'])]
    public function new(Request $request, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $titre = "Ajouter une nouvelle catégorie";
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        
        $form->handleRequest($request);
        
        $errors = $validator->validate($categorie);

        if ($form->isSubmitted() && $form->isValid() && count($errors) === 0 ) { 
            $entityManager->persist($categorie);
            $entityManager->flush();
            $this->addFlash('success', 'La catégorie a été ajoutée avec succès.');            
            return $this->redirectToRoute('categorie.index');   
        }

        return $this->render('categorie/new.html.twig', [
            'form' => $form->createView(),'titre'=>$titre,
            'errors'=>$errors
        ]);
    }


    #[Route('/{id}', name: 'show',methods:['GET'],requirements:['id'=>'\d+'])]

    public function show(CategorieRepository $categorieRepository,$id): Response
    {
        $categorie = $categorieRepository->find($id);
        return $this->render('categorie/show.html.twig', [
            'categorie'=>$categorie,
        ]);
    }


    #[Route('/{id}/edit', name: 'edit',methods:['POST','GET'],requirements:['id'=>'\d+'])]
    public function update(Request $request,EntityManagerInterface $entityManager,Categorie $categorie, ValidatorInterface $validator): Response
    {
        $titre ="Editer";
        $form = $this->createForm(CategorieType::class, $categorie,['is_edit'=>true]);
        $form->handleRequest($request);
        
        $errors = $validator->validate($categorie);
        if ($form->isSubmitted() && $form->isValid() && count($errors)===0) { 
            $entityManager->flush();
            $this->addFlash('success','Modification a été pris en compte');
            return $this->redirectToRoute('categorie.index');
        }
        return $this->render('categorie/edit.html.twig', [
            'form' => $form->createView(),'errors'=>$errors,'titre'=>$titre
        ]);
    }

    #[Route('/{id}/toggle-statut', name: 'toggle_statut', methods: ['GET'])]
    public function toggleStatus(Categorie $categorie, StatusToggleService $statusToggleService): Response
    {
        // Basculer le statut
        $statusToggleService->toggleStatus($categorie, 'statutCategorie');

        // Rediriger vers la indexe des catégories
        return $this->redirectToRoute('categorie.index');
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
public function delete(Categorie $categorie, Request $request, DeleteService $deleteService): Response
{
    $wordEntity = 'categorie';
    $redirect = $deleteService->handleDelete($categorie, $request, 'delete_'.$wordEntity, $wordEntity.'.index');

    if ($redirect) {
        $this->addFlash('success', ' supprimée avec succès.');
        return $this->redirect($redirect);
    }

    $this->addFlash('error', 'Échec de la suppression.');
    return $this->redirectToRoute($wordEntity.'.index');
}
}
