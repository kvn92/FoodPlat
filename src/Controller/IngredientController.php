<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use App\Repository\PaysRepository;
use App\Service\DeleteService;
use App\Service\StatusToggleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/ingredient', name: 'ingredient.')]

class IngredientController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository, DeleteService $deleteService): Response
    {
        $ingredients = $ingredientRepository->findAll();
        $nombres = $ingredientRepository->count();
        $deleteForms = [];
    
        // ✅ Vérifier que $pays n'est pas vide avant d'essayer de créer des formulaires de suppression
            foreach ($ingredients as $v) {
                $deleteForms[$v->getId()] = $deleteService
                    ->createDeleteForm($v, 'delete_utilisateurs')
                    ->createView();
            }
        
    
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
            'nombres' => $nombres,
            'secteur' => 'ingredient',
            'deleteForms' => $deleteForms, // Ajoute une vérification avant d'utiliser cette variable
        ]);
    }
    

    #[Route('/new', name: 'new',methods:['POST','GET'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        $errors = $validator->validate($ingredient);
    if ($form->isSubmitted() && $form->isValid() && count($errors) === 0) { 
        $entityManager->persist($ingredient);
        $entityManager->flush();
         $this->addFlash('success','Enregistrer');
         return $this->redirectToRoute('ingredient.index',[],303);

    }
        return $this->render('ingredient/new.html.twig', [
            'form' => $form->createView(),
            'errors'=>$errors
        ]);
    }

    #[Route('/{id}', name: 'show',methods:['GET'])]
    public function show(IngredientRepository $ingredientRepository,$id): Response
    {
        $ingredient = $ingredientRepository->find($id);
        return $this->render('ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit',methods:['POST','GET'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Ingredient $ingredient, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient,['is_edit'=>true]);
        $form->handleRequest($request);
        $errors = $validator->validate($ingredient);
        if ($form->isSubmitted() && $form->isValid() && count($errors) === 0) { 
            $entityManager->flush();
            $this->addFlash('success','Modifier');
            return $this->redirectToRoute('ingredient.index',[],303);
        }
        return $this->render('ingredient/edit.html.twig', [
            'form' => $form->createView(),
            'errors'=>$errors,
            'titre'=>'titre'
        ]);
    }


    #[Route('/{id}/toggle', name: 'toggle_statut', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function statut(StatusToggleService $statusToggleService, IngredientRepository $ingredientRepository, int $id): Response
    {
        $ingredient = $ingredientRepository->find($id);
    
        if (!$ingredient) {
            throw $this->createNotFoundException('Ingredient introuvable.');
        }
    
        $statusToggleService->toggleStatus($ingredient, 'statutIngredient');
        $this->addFlash('success', 'bravo');
        return $this->redirectToRoute('ingredient.index');
    }


    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Ingredient $ingredient, Request $request, DeleteService $deleteService): Response
    {
        $wordEntity = 'ingredient';
        $redirect = $deleteService->handleDelete($ingredient, $request, 'delete_'.$wordEntity, $wordEntity.'.index');
    
        if ($redirect) {
            $this->addFlash('success', ' supprimée avec succès.');
            return $this->redirect($redirect);
        }
    
        $this->addFlash('error', 'Échec de la suppression.');
        return $this->redirectToRoute($wordEntity.'.index');
    }
}
