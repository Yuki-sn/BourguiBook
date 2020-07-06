<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Activity;
use App\Form\ActiviteType;

class AddActivityController extends AbstractController
{
    /**
     * @Route("/ajouter_une_activiter/", name="add_activity")
     */
    public function index()
    {
        $newactivite = new Activity();

        $form = $this->createForm(ActiviteType::class , $newactivite);

        return $this->render('add_activity/addActivity.html.twig',[
            'form'=> $form->createView()
        ]);
    }
}
