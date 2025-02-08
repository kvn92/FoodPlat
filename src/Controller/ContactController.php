<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Entity\Utilisateur;
use App\Form\ContactType;
use App\Service\DeleteService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function conact(Request $request, MailerInterface $mailer): Response
    {

        $data = new ContactDTO();
        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $mail = (new TemplatedEmail())
            ->to('contact@demo.fr')
            ->from($data->email)
            ->subject('Demande de contact')
            ->htmlTemplate('emails/contact.html.twig')
            ->context(['data'=>$data]);
            $mailer->send($mail);
            $this->addFlash('success','Votre message a été bien enovyé ');
            
        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
public function delete(Utilisateur $utilisateur, Request $request, DeleteService $deleteService): Response
{
    $wordEntity = 'utilisateur';
    $redirect = $deleteService->handleDelete($utilisateur, $request, 'delete_'.$wordEntity, $wordEntity.'.index');

    if ($redirect) {
        $this->addFlash('success', ' supprimée avec succès.');
        return $this->redirect($redirect);
    }

    $this->addFlash('error', 'Échec de la suppression.');
    return $this->redirectToRoute($wordEntity.'.index');
}
}
