<?php

namespace App\Controller\User;

use App\Entity\DeviceUsageLog;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
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
            TextField::new('device', 'Device')
                ->onlyOnIndex(),
            TextField::new('title'),
            DateTimeField::new('startedAt'),
            DateTimeField::new('endedAt'),
            NumberField::new('energyUsedKWh', label: 'kWh')->setDisabled(true),
            TextField::new('durationPrettified', 'Duration')->setDisabled(true),
            TextField::new('device.user.email', 'Owner')
                ->onlyOnIndex()
        ];
    }
}
