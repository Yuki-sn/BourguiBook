<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Activity;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ActivityFake extends Fixture
{
    private $encoder;

    /**
     * Utilisation du constructeur pour récupérer le service de hashage des mots de passe via autowiring
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        // Création compte admin
        $admin = new User();
        // Hydratation du compte
        $admin
            ->setEmail('admin@a.a')
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($this->encoder->encodePassword($admin, 'aaaaaaaaA7/'))
            ->setIsVerified(true)
            ->setPseudonym('Yuki')
            ->setFirstname('clement')
            ->setLastname('martin')
            ->setPhoneNumber('0664930396')
            ->setGender('1')
            ->setRegistrationToken($faker->md5)

        ;
        // Persistance du compte
        $manager->persist($admin);

        for($i = 1; $i <= 60; $i++){

            $activity = $faker->numberBetween(0,3);

            if($activity == 1){
                $use = "hotel";
            }elseif($activity == 2){
                $use = 'restaurant';
            }else{
                $use ='autre';
            };

            $activity = new Activity;

            $activity
                ->setSiret('11111111111111')
                ->setTypeActivity($use)
                ->setTitle($faker->text(40))
                ->setCity($faker->city)
                ->setPostalCode('71956')
                ->setAddress($faker->address)
                ->setDescription($faker->paragraph(10))
                ->setStartDate($faker->dateTimeBetween('-3years', 'now')  )
                ->setEndDate($faker->dateTimeBetween('-1years', 'now')  )
                ->setEmail($faker->email)
                ->setPhoneNumber($faker->phoneNumber(10))
                ->setPublicationDate($faker->dateTimeBetween('-5years', 'now')  ) // Vu que la propriété "publicationDate" que nous avions créé est de type dateTime, cette dernière n'accepte que des instances de la classe Datetime (avec un \ devant car il s'agit d'une classe qui n'existe que dans le namespace global)
                ->setPictur($faker->text(20))
                ->setAuthor($admin)
                
            ;
            // Actuellement Doctrine ne "connais pas" encore notre nouvel article. Pour qu'il puisse le sauvegarder en base de données il faut d'abord le "porter à sa connaissance" avec la méthode persist()
            $manager->persist($activity);
        }
        // Sauvegarde en BDD des articles
        $manager->flush();
    }
}
