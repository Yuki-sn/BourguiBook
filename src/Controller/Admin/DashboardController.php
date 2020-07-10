<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use App\Entity\User;
use App\Entity\Activity;
use App\Entity\Comment;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin/", name="admin")
     */
    public function index(): Response
    {
        return parent::index();

        // redirect to some CRUD controller
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());

        // you can also redirect to different pages depending on the current user
        if ('jane' === $this->getUser()->getUsername()) {
            return $this->redirect('main/home.html.twig');
        }

        // you can also render some template to display a proper Dashboard
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('BourguiBook');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'icon class', EntityClass::class);

        yield MenuItem::section('Utilisateur');
        yield MenuItem::linkToCrud('Utilisateur', 'fa fa-user', User::class);

        yield MenuItem::section('Les activitées');
        yield MenuItem::linkToCrud('Les activitées', 'fa fa-tags', Activity::class);

        yield MenuItem::section('Commentaire');
        yield MenuItem::linkToCrud('Commentaire', 'fa fa-comment', Comment::class);

        
    }
}
