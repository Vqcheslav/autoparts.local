<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CarCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Car::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('manufacturer', 'manufacturer');
        yield TextField::new('brand');
        yield TextField::new('model');
        yield TextField::new('generation');
        yield TextField::new('engine');
        yield IntegerField::new('year');
    }
}
