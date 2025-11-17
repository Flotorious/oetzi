<?php

namespace App\Controller\User;

use App\Entity\UserEnergySnapshot;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Bundle\SecurityBundle\Security;

class UserEnergySnapshotUserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly Security $security
    ) {}

    public static function getEntityFqcn(): string
    {
        return UserEnergySnapshot::class;
    }

    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        $user = $this->security->getUser();
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.user = :user')
            ->setParameter('user', $user);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            DateTimeField::new('timestamp'),
            NumberField::new('consumptionKwh'),
            NumberField::new('consumptionDelta')->setDisabled(true),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Energy SmartMeter Snapshots')
            ->setPageTitle('new', 'Add Energy Snapshot')
            ->setPageTitle('edit', 'Edit Energy Snapshot');
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof UserEnergySnapshot) {
            return;
        }

        // Automatically set the user to the current logged-in user
        $user = $this->security->getUser();
        $entityInstance->setUser($user);

        // Calculate delta if there's a previous snapshot
        $previousSnapshot = $entityManager->getRepository(UserEnergySnapshot::class)
            ->createQueryBuilder('s')
            ->where('s.user = :user')
            ->andWhere('s.timestamp < :timestamp')
            ->setParameter('user', $user)
            ->setParameter('timestamp', $entityInstance->getTimestamp())
            ->orderBy('s.timestamp', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($previousSnapshot) {
            $delta = $entityInstance->getConsumptionKwh() - $previousSnapshot->getConsumptionKwh();
            $entityInstance->setConsumptionDelta($delta >= 0 ? round($delta, 4) : 0);
        } else {
            $entityInstance->setConsumptionDelta(null);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof UserEnergySnapshot) {
            return;
        }

        // Recalculate delta on update
        $user = $this->security->getUser();
        $previousSnapshot = $entityManager->getRepository(UserEnergySnapshot::class)
            ->createQueryBuilder('s')
            ->where('s.user = :user')
            ->andWhere('s.timestamp < :timestamp')
            ->andWhere('s.id != :id')
            ->setParameter('user', $user)
            ->setParameter('timestamp', $entityInstance->getTimestamp())
            ->setParameter('id', $entityInstance->getId())
            ->orderBy('s.timestamp', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($previousSnapshot) {
            $delta = $entityInstance->getConsumptionKwh() - $previousSnapshot->getConsumptionKwh();
            $entityInstance->setConsumptionDelta($delta >= 0 ? round($delta, 4) : 0);
        } else {
            $entityInstance->setConsumptionDelta(null);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}

