<?php

namespace App\Controller\Admin;

use App\Entity\Favorite;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class FavoriteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Favorite::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('user');
        yield AssociationField::new('autopart');
    }
}
