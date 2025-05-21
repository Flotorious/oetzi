<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        // If the user has ROLE_ADMIN, skip the filter entirely:
        if ($this->isGranted('ROLE_ADMIN')) {
            return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        }

        $userId = $this->getUser()->getId();
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.id = :userId')
            ->setParameter('userId', $userId);
    }

    public function findOneEntity($entityId)
    {
        $entity = parent::findOneEntity($entityId);

        if (!$this->isGranted('ROLE_ADMIN') && $entity instanceof User && $entity->getId() !== $this->getUser()->getId()) {
            throw new AccessDeniedException('You cannot access this user.');
        }

        return $entity;
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $actions;
        }

        return $actions
            ->disable(Action::NEW, Action::DELETE, Action::BATCH_DELETE);
    }
}
