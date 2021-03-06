<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Liste des contrôleurs des pages du système d'authentification du site
 */
class SecurityController extends AbstractController
{
    /**
     * Page de connexion
     * @Route("/connexion/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Redirige l'utilisateur sur la page d'accueil si il est déjà connecté
        if ($this->getUser()) {
            return $this->redirectToRoute('main_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Page de déconnexion
     *
     * @Route("/deconnexion/", name="app_logout")
     */
    public function logout()
    {
        // Le code ici ne sera jamais lu car la page de déconnexion est déjà gérée en interne par le bundle security
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
