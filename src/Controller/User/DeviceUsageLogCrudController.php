<?php

namespace App\Controller\User;

use App\Entity\DeviceUsageLog;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DeviceUsageLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DeviceUsageLog::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            DateTimeField::new('startedAt'),
            DateTimeField::new('endedAt'),
            IntegerField::new('duration'),
            NumberField::new('energyUsedKWh')
        ];
    }
}
