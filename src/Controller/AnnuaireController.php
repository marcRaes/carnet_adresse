<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Service\FormHandler;
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
    #[Route('/edit/{id}', name: 'app_annuaire_edit')]
    public function create(Request $request, FormHandler $formHandler, Contact $contact = null): Response
    {
        $isNew = false;
        if (!$contact) {
            $contact = new Contact();
            $isNew = true;
        }

        $form = $this->createForm(ContactType::class, $contact);

        if ($formHandler->handleForm($contact, $form, $request)) {
            if ($isNew) {
                $this->addFlash('success', 'Le contact ' . $contact->getNom() . ' ' . $contact->getPrenom() . ' a été créé avec succès');
            } else {
                $this->addFlash('success', 'Le contact ' . $contact->getNom() . ' ' . $contact->getPrenom() . ' a été modifié avec succès');
            }

            return $this->redirectToRoute('app_annuaire');
        }

        return $this->render('annuaire/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_annuaire_delete')]
    public function delete(Contact $contact, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($contact);
        $entityManager->flush();

        $this->addFlash('success', 'Le contact ' . $contact->getNom() . ' ' . $contact->getPrenom() . ' a été supprimé avec succès!');

        return $this->redirectToRoute('app_annuaire');
    }
}
