<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Activity;
use App\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Liste des contrôleurs des pages principales du site web. Le nom doit être toujours identique au nom du fichier.
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function index()
    {
        $activityRepository = $this->getDoctrine()->getRepository(Activity::class);

        $activity = $activityRepository->findActivityForHome();

        return $this->render('main/home.html.twig', [
            'activitys' => $activity
        ]);
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
     * @Route("/contact/", name="contact")
     */
    public function contact()
    {

        // Cette page appellera la vue templates/main/contact.html.twig
        return $this->render('main/contact.html.twig');
    }
}
