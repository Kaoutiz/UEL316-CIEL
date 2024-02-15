<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoriesRepository;
use App\Repository\ProduitsRepository;
use App\Repository\UserRepository;
use App\Entity\Categories;
use App\Entity\Produits;
use App\Entity\User;

class DashboardController extends AbstractDashboardController
{

    private $categoriesRepository;
    private $produitsRepository;
    private $userRepository;
    public function __construct(CategoriesRepository $categoriesRepository,ProduitsRepository $produitsRepository,UserRepository $userRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
        $this->produitsRepository = $produitsRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $users = $this->userRepository->findAll();
        $categories = $this->categoriesRepository->findAll();
        $produits = $this->produitsRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'categories' => $categories,
            'produits' => $produits,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('UEL316 CIEL');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Font-End', 'fa fa-home', 'app_home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-message', Categories::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-list', Produits::class);
        yield MenuItem::linkToLogout('Logout', 'fas fa-sign-out');
    }
}
