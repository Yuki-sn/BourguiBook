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

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin/", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
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

        yield MenuItem::section('Activitées');
        yield MenuItem::linkToCrud('Activitées', 'fa fa-house', Activity::class);
    }
}
