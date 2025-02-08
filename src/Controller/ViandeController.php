<?php

namespace App\Controller;

use App\Entity\Viande;
use App\Form\ViandeType;
use App\Repository\ViandeRepository;
use App\Service\DeleteService;
use App\Service\StatusToggleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/viande', name: 'viande.')]

class ViandeController extends AbstractController
{
    #[Route('', name: 'index',methods:['GET'])]
    public function index(ViandeRepository $viandeRepository,DeleteService $deleteService, ): Response
    {
        $viande = $viandeRepository->findAll();
        $nombres = $viandeRepository->countViande();
        $deleteForms = [];
foreach ($viande as $v) { // Ne pas écraser la variable $viande
    $deleteForms[$v->getId()] = $deleteService
        ->createDeleteForm($v, 'delete_viande')
        ->createView();
}

return $this->render('viande/index.html.twig', [
    'viandes' => $viande,
    'nombres' => $nombres,
    'secteur' => 'Viande',
    'deleteForms' => $deleteForms, // Ajouter les formulaires de suppression à la vue
]);

    }

    
    #[Route('/new', name: 'new',methods:['POST','GET'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $viande = new Viande();
        $form = $this->createForm(ViandeType::class, $viande,['is_edit'=>true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $entityManager->persist($viande);
            $entityManager->flush();
            $this->addFlash('success','Enregrister');
            return $this->redirectToRoute('viande.index',[],303);
        }
        return $this->render('viande/new.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }


    #[Route('/{id}', name: 'show',methods:['GET'])]
    public function show(ViandeRepository $viandeRepository,$id): Response
    {
        $viande = $viandeRepository->find($id);
 

        return $this->render('viande/show.html.twig', [
            'viande' => $viande,
       
        ]);
    }


    #[Route('/{id}/edit', name: 'edit', methods: ['POST', 'GET'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Viande $viande)
    {
        $form = $this->createForm(ViandeType::class, $viande, ['is_edit' => true]);
        $form->handleRequest($request);
    
    
        if ($form->isSubmitted() && $form->isValid()) { 
            $entityManager->flush();
            $this->addFlash('success', 'Bien modifié');
            return $this->redirectToRoute('viande.index', [], 303);
        }
    
        return $this->render('viande/edit.html.twig', [
            'form' => $form,
            'titre' => "edit"
        ]);
    }
    




    #[Route('/{id}/toggle', name: 'toggle_statut', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function statut(StatusToggleService $statusToggleService, ViandeRepository $viandeRepository, int $id): Response
    {
        $viande = $viandeRepository->find($id);
    
        if (!$viande) {
            throw $this->createNotFoundException('Viande introuvable.');
        }
    
        $statusToggleService->toggleStatus($viande, 'statutViande');
        $this->addFlash('success', 'bravo');
        return $this->redirectToRoute('viande.index');
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Viande $viande, Request $request, DeleteService $deleteService): Response
    {
        $wordEntity = 'viande';
        $redirect = $deleteService->handleDelete($viande, $request, 'delete_'.$wordEntity, $wordEntity.'.index');

        if ($redirect) {
            $this->addFlash('success', ' supprimée avec succès.');
            return $this->redirect($redirect);
        }

        $this->addFlash('error', 'Échec de la suppression.');
        return $this->redirectToRoute($wordEntity.'.index');
    }
}
