<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use App\Entity\Category;

use App\Entity\Product;
use App\Entity\User;
use App\Entity\Order;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(OrderCrudController::class)->generateUrl());
       
      
    }

    

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Ma Petite Boutique');
    }

   
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('orders', 'fas fa-shopping-cart', Order::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Products', 'fas fa-tag', Product::class);
        yield MenuItem::linkToCrud('carriers', 'fas fa-truck', Carrier::class);
      
        
    }

}