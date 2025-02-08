<?php

namespace App\Controller;

use App\Entity\Difficulte;
use App\Form\DifficulteType;
use App\Repository\DifficulteRepository;
use App\Service\DeleteService;
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
    #[Route('', name: 'index',methods:['GET'])]
    public function index(DifficulteRepository  $difficulteRepository, DeleteService $deleteService): Response
    {
        $titre = "Difficulte";
        $difficulte = $difficulteRepository->findAll();
        $nombres = $difficulteRepository->countDifficulte();
        $deleteForms = [];

        foreach ($difficulte as $v) { // Ne pas écraser la variable $viande
            $deleteForms[$v->getId()] = $deleteService
                ->createDeleteForm($v, 'delete_difficulte')
                ->createView();
        }
        return $this->render('difficulte/index.html.twig', [
            'difficultes' => $difficulte,
            'titre'=>$titre,
            'nombres'=>$nombres,
            'secteur'=>'Difficultes',
            'deleteForms' => $deleteForms, // Ajouter les formulaires de suppression à la vue

        ]);
    
}
    #[Route('/new', name: 'new',methods:['GET','POST'])]
    public function new (Request $request, EntityManagerInterface $entityManager): Response
    {
        $difficulte = new Difficulte();
        $form = $this->createForm(DifficulteType::class, $difficulte);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $entityManager->persist($difficulte);
            $entityManager->flush();
            $this->addFlash('success','Ajouter dans la base de donnée');
            return $this->redirectToRoute('difficulte.index');
        }
        return $this->render('difficulte/new.html.twig', ['form'=>$form->createView()
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
    public function update(Request $request,EntityManagerInterface $entityManager,Difficulte $difficulte,): Response
    {
        $form = $this->createForm(DifficulteType::class, $difficulte,['is_edit'=>true]);
        $form->handleRequest($request);
      
        if ($form->isSubmitted() && $form->isValid() ) { 
            $entityManager->flush();
            $this->addFlash('success','Modification a été pris en compte');
            return $this->redirectToRoute('difficulte.index');
        }
        return $this->render('difficulte/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}/toggle-status', name: 'toggle_status', methods: ['GET'])]

    public function togglesStatut(Difficulte $difficulte, StatusToggleService $statusToggleService){

        $statusToggleService->toggleStatus($difficulte, 'statutDifficulte');
        return $this->redirectToRoute('difficulte.index');

    }
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Difficulte $difficulte, Request $request, DeleteService $deleteService): Response
    {
        $wordEntity = 'difficulte';
        $redirect = $deleteService->handleDelete($difficulte, $request, 'delete_'.$wordEntity, $wordEntity.'.index');
    
        if ($redirect) {
            $this->addFlash('success', ' supprimée avec succès.');
            return $this->redirect($redirect);
        }
    
        $this->addFlash('error', 'Échec de la suppression.');
        return $this->redirectToRoute($wordEntity.'.index');
    }
    
    
}
