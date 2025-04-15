<?php

namespace App\Controller\User;

use App\Entity\Device;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserDeviceCrudController extends AbstractCrudController
{

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
}
