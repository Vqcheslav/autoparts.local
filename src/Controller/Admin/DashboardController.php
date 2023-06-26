<?php

namespace App\Controller\Admin;

use App\Entity\Autopart;
use App\Entity\Car;
use App\Entity\Favorite;
use App\Entity\Manufacturer;
use App\Entity\AutopartOrder;
use App\Entity\User;
use App\Entity\Warehouse;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
//        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(AutopartCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Autoparts Local')
            ->setFaviconPath('/favicon.svg')
            ->setTranslationDomain('admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'homepage');
        yield MenuItem::linkToCrud('Autoparts', 'fas fa-table', Autopart::class);
        yield MenuItem::linkToCrud('Cars', 'fas fa-car', Car::class);
        yield MenuItem::linkToCrud('Warehouses', 'fas fa-warehouse', Warehouse::class);
        yield MenuItem::linkToCrud('AutopartOrders', 'fas fa-list', AutopartOrder::class);
        yield MenuItem::linkToCrud('Manufacturers', 'fas fa-building', Manufacturer::class);
        yield MenuItem::linkToCrud('Favorites', 'fas fa-heart', Favorite::class);

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        }
    }
}
