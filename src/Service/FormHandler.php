<?php

namespace App\Service;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    /**
    * Gère la soumission et la validation du formulaire.
    * @param Contact $contact
    * @param FormInterface $form
    * @param Request $request
    * @return bool true si tout est ok
    */
    public function handleForm(Contact $contact, FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }
}
