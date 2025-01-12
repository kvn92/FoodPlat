<?php

namespace App\Controller;

use App\Entity\Difficulte;
use App\Form\DifficulteType;
use App\Repository\DifficulteRepository;
use App\Service\StatusToggleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/difficulte', name: 'difficulte.')]
class DifficulteController extends AbstractController
{
    #[Route('', name: 'list',methods:['GET'])]
    public function list(DifficulteRepository  $difficulteRepository): Response
    {
        $titre = "Difficulte";
        $difficulte = $difficulteRepository->findAll();
        $nombreDifficulte = $difficulteRepository->countDifficulte();
        return $this->render('difficulte/index.html.twig', [
            'difficultes' => $difficulte,
            'titre'=>$titre,
            'nombreDifficulte'=>$nombreDifficulte,
            'secteur'=>'Difficultes'
        ]);
    }

    #[Route('/new', name: 'new',methods:['GET','POST'])]
    public function new (Request $request, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $difficulte = new Difficulte();
        $form = $this->createForm(DifficulteType::class, $difficulte);
        $form->handleRequest($request);
        $errors = $validator->validate($difficulte);
        if ($form->isSubmitted() && $form->isValid()&& count($errors) === 0) { 
            $entityManager->persist($difficulte);
            $entityManager->flush();
            $this->addFlash('success','Ajouter dans la base de donnée');
            return $this->redirectToRoute('difficulte.list');
        }
        return $this->render('difficulte/new.html.twig', [
            'form' => $form->createView(),'errors'=>$errors
        ]);
    }
 #[Route('/{id}', name: 'show',methods:['GET'], requirements:['id'=>'\d+'])]
    public function show(DifficulteRepository $difficultes,$id): Response
    {
        
        $difficulte = $difficultes->find($id);

        return $this->render('difficulte/show.html.twig', [
            'difficulte' => $difficulte,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit',methods:['POST','GET'],requirements:['id'=>'\d+'])]
    public function update(Request $request,EntityManagerInterface $entityManager,Difficulte $difficulte, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(DifficulteType::class, $difficulte,['boutonForm'=>false]);
        $form->handleRequest($request);
        
        $errors = $validator->validate($difficulte);
        if ($form->isSubmitted() && $form->isValid() && count($errors)===0) { 
            $entityManager->flush();
            $this->addFlash('success','Modification a été pris en compte');
            return $this->redirectToRoute('difficulte.list');
        }
        return $this->render('difficulte/edit.html.twig', [
            'form' => $form->createView(),'errors'=>$errors
        ]);
    }


    #[Route('/{id}/toggle-status', name: 'toggle_status', methods: ['GET'])]

    public function togglesStatut(Difficulte $difficulte, StatusToggleService $statusToggleService){

        $statusToggleService->toggleStatus($difficulte, 'statutDifficulte');
        return $this->redirectToRoute('difficulte.list');

    }
    
}
