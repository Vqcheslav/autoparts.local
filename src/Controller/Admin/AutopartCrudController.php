<?php

namespace App\Controller\Admin;

use App\Entity\Autopart;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AutopartCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Autopart::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('car');
        yield AssociationField::new('warehouse');
        yield TextField::new('title');
        yield TextEditorField::new('description');
    }
}
