<?php

namespace App\Controller;

use App\Service\StatusToggleService;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/categorie', name: 'categorie.')]

class CategorieController extends AbstractController
{
    
    #[Route('', name: 'list')]
    public function list(CategorieRepository $categorieRepository): Response
    {
        $titre = 'Liste de categorie';
        $categorie = $categorieRepository->findAll();
        $nombreCategorie = $categorieRepository->countCategories(); // Plus de problème ici
    
        return $this->render('categorie/index.html.twig', [
            'categorie' => $categorie,
            'titre' => $titre,
            'nombreCategorie' => $nombreCategorie,
            'secteur'=> 'catégorie'
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
            return $this->redirectToRoute('categorie.list');   
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
        $form = $this->createForm(CategorieType::class, $categorie,['boutonForm'=>false]);
        $form->handleRequest($request);
        
        $errors = $validator->validate($categorie);
        if ($form->isSubmitted() && $form->isValid() && count($errors)===0) { 
            $entityManager->flush();
            $this->addFlash('success','Modification a été pris en compte');
            return $this->redirectToRoute('categorie.list');
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

        // Rediriger vers la liste des catégories
        return $this->redirectToRoute('categorie.list');
    }
}
