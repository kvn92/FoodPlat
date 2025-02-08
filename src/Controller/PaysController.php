<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Form\PaysType;
use App\Repository\PaysRepository;
use App\Service\DeleteService;
use App\Service\StatusToggleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pays', name: 'pays.')]

class PaysController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(PaysRepository $paysRepository, DeleteService $deleteService): Response
    {
        $pays = $paysRepository->findAll();
        $nombres = $paysRepository->count();
        $deleteForms = [];
    
        foreach ($pays as $v) { 
            $deleteForms[$v->getId()] = $deleteService
                ->createDeleteForm($v, 'delete_pays')
                ->createView();
        }
    
        // ✅ Correction : Assurer que le return est en dehors de la boucle
        return $this->render('pays/index.html.twig', [
            'pays' => $pays,
            'nombres' => $nombres,
            'secteur' => 'Pays',
            'deleteForms' => $deleteForms, // Ajouter les formulaires de suppression à la vue
        ]);
    }
    
    #[Route('/new', name: 'new',methods:['POST','GET'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pays = new Pays();
        $form = $this->createForm(PaysType::class, $pays);
        $form->handleRequest($request);
 
    if ($form->isSubmitted() && $form->isValid()) { 
        $entityManager->persist($pays);
        $entityManager->flush();
         $this->addFlash('success','Enregistrer');
         return $this->redirectToRoute('pays.index',[],303);

    }
        return $this->render('pays/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/{id}', name: 'show',methods:['GET'])]
    public function show(PaysRepository $paysRepository,$id): Response
    {
        $pays = $paysRepository->find($id);
        return $this->render('pays/show.html.twig', [
            'pays' => $pays,
        ]);
    }


    #[Route('/{id}/edit', name: 'edit',methods:['POST','GET'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Pays $pays): Response
    {
        $form = $this->createForm(PaysType::class, $pays,['is_edit'=>true]);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) { 
            $entityManager->flush();
            $this->addFlash('success','Modifier');
            return $this->redirectToRoute('pays.index',[],303);
        }
        return $this->render('pays/edit.html.twig', [
            'form' => $form->createView(),
            'titre'=>'titre'
        ]);
    }

    #[Route('/{id}/toggle', name: 'toggle_statut', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function statut(StatusToggleService $statusToggleService, PaysRepository $paysRepository, int $id): Response
    {
        $pays = $paysRepository->find($id);
    
        if (!$pays) {
            throw $this->createNotFoundException('Pays introuvable.');
        }
    
        $statusToggleService->toggleStatus($pays, 'statutPays');
        $this->addFlash('success', 'bravo');
        return $this->redirectToRoute('pays.index');
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
public function delete(Pays $pays, Request $request, DeleteService $deleteService): Response
{
    $wordEntity = 'pays';
    $redirect = $deleteService->handleDelete($pays, $request, 'delete_'.$wordEntity, $wordEntity.'.index');

    if ($redirect) {
        $this->addFlash('success', ' supprimée avec succès.');
        return $this->redirect($redirect);
    }

    $this->addFlash('error', 'Échec de la suppression.');
    return $this->redirectToRoute($wordEntity.'.index');
}

    

}
