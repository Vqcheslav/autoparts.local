<?php

namespace App\Controller\Admin;

use App\Entity\AutopartOrder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AutopartOrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AutopartOrder::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('user');
        yield AssociationField::new('autopart');
        yield NumberField::new('amount');
        yield TextField::new('status');
    }
}
