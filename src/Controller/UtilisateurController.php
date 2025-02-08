<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Service\DeleteService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/admin', name: 'admin.')]

class UtilisateurController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(UtilisateurRepository $utilisateurRepository,DeleteService $deleteService): Response
    {

        $utilisateurs = $utilisateurRepository->findAll();
        $nbUtilisateurs = $utilisateurRepository->countUtilisateur();
        $deleteForms = [];
        foreach ($utilisateurs as $v) { // Ne pas écraser la variable $viande
            $deleteForms[$v->getId()] = $deleteService
                ->createDeleteForm($v, 'delete_utilisateurs')
                ->createView();
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
            'nbUtilisateurs'=>$nbUtilisateurs,
            'secteur'=>'utlisateurs',
            'deleteForms' => $deleteForms, // Ajouter les formulaires de suppression à la vue

        ]);
    }
    }
    #[Route('/new', name: 'new', methods: ['POST', 'GET'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        $utilisateur = new Utilisateur();
    
        // Création du formulaire
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
    
        // Validation du formulaire
        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {
            // Hachage du mot de passe avant la sauvegarde
            $hashedPassword = $hasher->hashPassword(
                $utilisateur,
                $utilisateur->getPassword() // Le mot de passe entré dans le formulaire
            );
            $utilisateur->setPassword($hashedPassword);
            
            // Enregistrement dans la base de données
            $entityManager->persist($utilisateur);
            $entityManager->flush();
    
            // Message flash
            $this->addFlash('success', 'Utilisateur ajouté');
            return $this->redirectToRoute('admin.index');
        }
    
        // Rendu du formulaire
        return $this->render('utilisateur/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

        #[Route('/{id}', name: 'show',methods:['GET'],requirements:['id'=>'\d+'])]
    public function show(UtilisateurRepository $utilisateurRepository,$id ): Response
    {

        $utilisateur = $utilisateurRepository->find($id);

        
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }


    #[Route('/{id}/edit', name: 'edit',methods:['GET','POST'],requirements:['id'=>'\d+'])]
    public function edit(Request $request, Utilisateur  $utilisateur, EntityManagerInterface $entityManager ): Response
    {
        $upader = new UtilisateurType();
        $form = $this->createForm(UtilisateurType::class, $utilisateur,['is_edit'=>true]);
        $form->handleRequest($request);
        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) { 
          $file = $form->get('photo')->getData();
          /** @var UploadedFile $file */
          $file = $form->get('photo')->getData();
          if ($file) {
              // Vérifier l'extension et normaliser le nom
              $fileName = $utilisateur->getId() . '.' . $file->guessExtension();
  
              // Déplacer le fichier vers le répertoire spécifié
              $destination = $this->getParameter('kernel.project_dir') . '/public/admin/images';
              $file->move($destination, $fileName);
  
              // Mettre à jour le nom du fichier dans l'entité
              $utilisateur->setPhoto($fileName);
  
              // Sauvegarder à nouveau pour mettre à jour le fichier
              $entityManager->flush();
          }
        }
        
        return $this->render('utilisateur/edit.html.twig', [
            'form' => $form,
            'titre'=>'Modifier'
        ]);
    }
}
