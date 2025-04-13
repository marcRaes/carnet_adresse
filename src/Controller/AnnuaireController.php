<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AnnuaireController extends AbstractController
{
    #[Route('/', name: 'app_annuaire')]
    public function index(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findAll();

        return $this->render('annuaire/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/create', name: 'app_annuaire_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('success', 'Le contact ' . $contact->getNom() . ' ' . $contact->getPrenom() . ' a été créé avec succès');

            return $this->redirectToRoute('app_annuaire');
        }

        return $this->render('annuaire/create.html.twig', [
            'form' => $form,
        ]);
    }
}
