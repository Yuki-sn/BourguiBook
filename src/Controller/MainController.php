<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Activity;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function index()
    {
        $articleRepository = $this->getDoctrine()->getRepository(Activity::class);
        $foundArticles = $articleRepository->findAll();

        dump($foundArticles);

        return $this->render('main/home.html.twig');
    }
}
