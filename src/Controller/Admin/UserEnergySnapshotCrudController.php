<?php

namespace App\Controller\Admin;

use App\Entity\UserEnergySnapshot;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserEnergySnapshotCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserEnergySnapshot::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            DateTimeField::new('timestamp'),
            NumberField::new('consumptionKwh'),
            NumberField::new('consumptionDelta'),
            TextField::new('user.email', 'Owner')->onlyOnIndex(),
        ];
    }
}
