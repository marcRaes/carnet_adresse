<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private const NUM_CONTACTS = 50;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $data = $this->getData($faker);

        foreach ($data as $contactData) {
            $contact = $this->createContact($contactData);
            $manager->persist($contact);
        }

        $manager->flush();
    }

    private function getData(Generator $faker): array
    {
        $data = [];
        for ($i = 0; $i <= self::NUM_CONTACTS; $i++) {
            $data[] = [
                'nom' => $faker->lastName(),
                'prenom' => $faker->firstName(),
                'telephone' => $faker->phoneNumber(),
                'adresse' => $faker->address(),
                'mail' => $faker->email(),
            ];
        }

        return $data;
    }

    private function createContact(array $data): Contact
    {
        $contact = new Contact();

        $contact->setNom($data['nom'])
            ->setPrenom($data['prenom'])
            ->setTelephone($data['telephone'])
            ->setAdresse($data['adresse'])
            ->setMail($data['mail']);

        return $contact;
    }
}
