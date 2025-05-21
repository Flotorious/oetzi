<?php

namespace App\Controller\User;

use App\Entity\Device;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\DeviceUsageLog;


class UserDeviceCrudController extends AbstractCrudController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator) {}

    public static function getEntityFqcn(): string
    {
        return Device::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $device = new Device();
        $device->setUser($this->getUser());

        return $device;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.user = :user')
            ->setParameter('user', $this->getUser());
    }

    #[Route('/profile/device-create-defaults', name: 'user_device_create_defaults')]
    public function createDefaultDevices(EntityManagerInterface $em): RedirectResponse
    {
        $user = $this->getUser();

        if ($user->getDevices()->count() > 0) {
            $this->addFlash('warning', 'You already have devices.');

            return $this->redirect($this->adminUrlGenerator
                ->setDashboard(UserDashboardController::class)
                ->setController(self::class)
                ->setAction(Crud::PAGE_INDEX)
                ->generateUrl());
        }

        $defaults = [
            ['name' => 'Fridge', 'power' => 150],
            ['name' => 'TV', 'power' => 100],
            ['name' => 'Washing Machine', 'power' => 500],
            ['name' => 'Laptop', 'power' => 65],
            ['name' => 'Microwave', 'power' => 1200],
            ['name' => 'Oven', 'power' => 2000],
            ['name' => 'Air Conditioner', 'power' => 1500],
            ['name' => 'Heater', 'power' => 1000],
            ['name' => 'Vacuum Cleaner', 'power' => 800],
            ['name' => 'Water Boiler', 'power' => 1800],
        ];

        foreach ($defaults as $item) {
            $device = new Device();
            $device->setUser($user);
            $device->setName($item['name']);
            $device->setPowerWatt($item['power']);
            $device->setIsActive(false);
            $em->persist($device);
        }

        $em->flush();

        $this->addFlash('success', 'Default devices created!');
        return $this->redirect($this->adminUrlGenerator
            ->setDashboard(UserDashboardController::class)
            ->setController(self::class)
            ->setAction(Crud::PAGE_INDEX)
            ->generateUrl());
    }

    public function findOneEntity($entityId)
    {
        $device = parent::findOneEntity($entityId);

        if ($device->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $device;
    }

    public function configureActions(Actions $actions): Actions
    {
        $createDefaults = Action::new('createDefaultDevices', 'Create Default Devices')
            ->linkToRoute('user_device_create_defaults')
            ->createAsGlobalAction();

        return $actions
            ->add(Crud::PAGE_INDEX, $createDefaults);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('description'),
            NumberField::new('powerWatt'),
            BooleanField::new('isActive', 'Active'),
            CollectionField::new('deviceUsageLogs')
                ->useEntryCrudForm(DeviceUsageLogCrudController::class)
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->setColumns('col-md-12 col-xxl-12')
                ->onlyOnForms()
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined();
    }
    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if (!$entityInstance instanceof Device) {
            return;
        }

        $logRepo = $em->getRepository(DeviceUsageLog::class);
        $isActiveNow = $entityInstance->isActive();
        $hasOpenLog = $entityInstance->getLastOpenUsageLog() !== null;

        if ($isActiveNow && !$hasOpenLog) {
            $log = new DeviceUsageLog();
            $log->setDevice($entityInstance);
            $log->setStartedAt(new \DateTimeImmutable());
            $entityInstance->addDeviceUsageLog($log);
            $em->persist($log);
        }

        if (!$isActiveNow && $hasOpenLog) {
            $log = $entityInstance->getLastOpenUsageLog();
            $log->setEndedAt(new \DateTimeImmutable());
            $log->calculateEnergyUsage();
            $log->setTitleFromData();
        }

        $em->flush();
    }
}
