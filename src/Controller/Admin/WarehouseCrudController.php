<?php

namespace App\Controller\Admin;

use App\Entity\Warehouse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WarehouseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Warehouse::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('address');
        yield TextField::new('opening_hours');
        yield TextField::new('working_days');
        yield TextField::new('phone_number');
    }
}
