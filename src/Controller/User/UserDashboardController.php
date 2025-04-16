<?php

namespace App\Controller\User;

use App\Controller\Admin\UserCrudController;
use App\Entity\Device;
use App\Entity\DeviceUsageLog;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

#[AdminDashboard(routePath: '/profile', routeName: 'app_user_dashboard')]
class UserDashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
    }


    #[Route('/dashboard', name: 'user_dashboard_index')]
    public function index(): Response
    {
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('user_dashboard/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('User Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        $userId = $this->getUser()->getId();

        $editProfileUrl = $this->adminUrlGenerator
            ->setController(UserCrudController::class)
            ->setAction('edit')
            ->setEntityId($userId)
            ->generateUrl();

        yield MenuItem::linkToUrl('My Profile', 'fa fa-user', $editProfileUrl);
        yield MenuItem::linkToCrud('My Devices', 'fas fa-microchip', Device::class);
        yield MenuItem::linkToLogout('Logout', 'fa fa-sign-out');
        yield MenuItem::linkToCrud('Usage Logs', 'fa fa-clock', DeviceUsageLog::class);
    }
}
