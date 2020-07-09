<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Activity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Liste des contrôleurs des pages principales du site web. Le nom doit être toujours identique au nom du fichier.
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index()
    {
        $articleRepository = $this->getDoctrine()->getRepository(Activity::class);
        $foundArticles = $articleRepository->findAll();

        dump($foundArticles);

        return $this->render('main/home.html.twig');
    }

    /**
     * @Route("/mon-profil/", name="profil")
     * @Security("is_granted('ROLE_USER')")
     */
    public function profil()
    {
        // Si la personne qui essaye de venir sur cette page n'est pas connectée, elle sera redirigée à la page de connexion par le firewall

        return $this->render('main/profil.html.twig');
    }

    /**
     * @Route("/admin/", name="admin")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function admin()
    {
        // Si la personne qui essaye de venir sur cette page n'a pas le rôle ROLE_ADMIN, elle sera redirigée à la page de connexion si elle n'est pas connecté ou bien sur une page 403 si elle l'est mais n'est pas admin.

        return $this->render('main/admin.html.twig');
    }

    /**
     * On ajoute une sécurité pour être sûr que l'utilisateur est bien connecté, sinon on ne pourra pas changer son mot de passe.
     *
     * @Route("/changer-mot-de-passe/", name="change_password_test")
     * @Security("is_granted('ROLE_USER')")
     */
    public function changePasswordTest(UserPasswordEncoderInterface $encoder)
    {

        // On récupère l'utilisateur connecté
        $connectedUser = $this->getUser();

        // Définition complétement arbitraire d'un nouveau mot de passe, le mieux serait de récupérer un nouveau mot de passe depuis un formulaire
        $newPassword = 'azerty';

        // Grâce au service, on génère un nouveau hash de notre nouveau mot de passe
        $hashOfNewPassword = $encoder->encodePassword($connectedUser, $newPassword);

        // On change l'ancien mot de passe hashé par le nouveau que l'on a généré juste au dessus
        $connectedUser->setPassword( $hashOfNewPassword );


        // Sauvegarde des modifications en BDD grâce au manager des entités
        $em = $this->getDoctrine()->getManager();

        $em->flush();


        return $this->render('main/password_changed.html.twig');

    }

}
