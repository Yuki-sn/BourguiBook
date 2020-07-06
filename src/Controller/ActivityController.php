<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{
    /**
     * @Route("/activity", name="main_activity")
     */
    public function index()
    {
        return $this->render('activity/allActivity.html.twig');
    }
}
