<?php

namespace App\Controller\User;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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

    public function findOneEntity($entityId)
    {
        $device = parent::findOneEntity($entityId);

        if ($device->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $device;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('description'),
            NumberField::new('powerWatt'),
            BooleanField::new('isActive', 'Active'),
            BooleanField::new('isTemplate'),
            CollectionField::new('deviceUsageLogs')
                ->useEntryCrudForm(DeviceUsageLogCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->onlyOnForms()
        ];
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
