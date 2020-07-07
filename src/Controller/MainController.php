<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Activity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * @Route("/profil", name="profil")
     * @Security("is_granted('ROLE_USER')")
     */
    public function profil()
    {
        // Si la personne qui essaye de venir sur cette page n'est pas connectée, elle sera redirigée à la page de connexion par le firewall

        return $this->render('main/profil.html.twig');
    }

    /**
     * @Route("/administration", name="admin")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function admin()
    {
        // Si la personne qui essaye de venir sur cette page n'a pas le rôle ROLE_ADMIN, elle sera redirigée à la page de connexion si elle n'est pas connecté ou bien sur une page 403 si elle l'est mais n'est pas admin.

        return $this->render('main/admin.html.twig');
    }
}
