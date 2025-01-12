<?php

namespace App\Controller;

use App\Entity\TypeRepas;
use App\Form\TypeRepasType;
use App\Repository\TypeRepasRepository;
use App\Service\StatusToggleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/type-repas', name: 'type-repas.')]
class TypeRepasController extends AbstractController
{
    #[Route('', name: 'list')]
    public function index(TypeRepasRepository $typeRepasRepository): Response
    {
        $type = $typeRepasRepository->findAll();
        $nombreType = $typeRepasRepository->countTypeRepas();
        return $this->render('type_repas/index.html.twig', [
            'type' => $type,
             'nombreType' => $nombreType,
            'secteur'=> 'Type de repas'
        ]);
    }


    #[Route('/new', name: 'new',methods:['GET','POST'])]
    public function new (Request $request, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $typeRepas = new TypeRepas();
        $form = $this->createForm(TypeRepasType::class, $typeRepas);
        $form->handleRequest($request);
        $errors = $validator->validate($typeRepas);
        if ($form->isSubmitted() && $form->isValid()&& count($errors) === 0) { 
            $entityManager->persist($typeRepas);
            $entityManager->flush();
            $this->addFlash('success','Ajouter dans la base de donnée');
            return $this->redirectToRoute('type-repas.list');
        }
        return $this->render('type_repas/new.html.twig', [
            'form' => $form->createView(),'errors'=>$errors
        ]);
    }


    #[Route('/{id}', name: 'show',methods:['GET'],requirements:['id'=>'\d+'])]
    public function show(TypeRepasRepository  $typeRepasRepository,$id): Response
    {
        $type = $typeRepasRepository->find($id);
        return $this->render('type_repas/show.html.twig', [
            'type'=>$type,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit',methods:['POST','GET'],requirements:['id'=>'\d+'])]
    public function update(Request $request,EntityManagerInterface $entityManager,TypeRepas $typeRepas, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(TypeRepasType::class, $typeRepas,['boutonForm'=>false]);
        $form->handleRequest($request);
        
        $errors = $validator->validate($typeRepas);
        if ($form->isSubmitted() && $form->isValid() && count($errors)===0) { 
            $entityManager->flush();
            $this->addFlash('success','Modification a été pris en compte');
            return $this->redirectToRoute('type-repas.list');
        }
        return $this->render('type_repas/edit.html.twig', [
            'form' => $form->createView(),'errors'=>$errors,'titre'=>'Edit'
        ]);
    }

    #[Route('/{id}/toggle', name: 'toggle_statut',methods:['GET'],requirements:['id'=>'\d+'])]

    public function statut(StatusToggleService $statusToggleService, TypeRepas $typeRepas){
        $statusToggleService->toggleStatus($typeRepas, 'statutTypeRepas');
        $this->addFlash('success','bravo');
        return $this->redirectToRoute('type-repas.list');
    }


}
