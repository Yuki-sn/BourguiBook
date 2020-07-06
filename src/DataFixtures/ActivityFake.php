<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Activity;
use Faker;

class ActivityFake extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for($i = 1; $i <= 20; $i++){

            $activity = new Activity;

            $activity
                ->setTitle($faker->title)
                ->setCity($faker->city)
                ->setPostalCode('71956')
                ->setAddress($faker->address)
                ->setDescription($faker->paragraph(10))
                ->setStartDate($faker->dateTimeBetween('-3years', 'now')  )
                ->setEndDate($faker->dateTimeBetween('-1years', 'now')  )
                ->setEmail($faker->email)
                ->setPhoneNumber('1234567891')
                ->setPublicationDate($faker->dateTimeBetween('-5years', 'now')  ) // Vu que la propriété "publicationDate" que nous avions créé est de type dateTime, cette dernière n'accepte que des instances de la classe Datetime (avec un \ devant car il s'agit d'une classe qui n'existe que dans le namespace global)
            ;



            // Actuellement Doctrine ne "connais pas" encore notre nouvel article. Pour qu'il puisse le sauvegarder en base de données il faut d'abord le "porter à sa connaissance" avec la méthode persist()
            $manager->persist($activity);


        }

        // Sauvegarde en BDD des articles
        $manager->flush();
    }
}
