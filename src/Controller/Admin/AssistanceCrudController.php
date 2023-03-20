<?php

namespace App\Controller\Admin;

use App\Entity\Assistance;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AssistanceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Assistance::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstname', 'Nom'),
            TextField::new('lastname', 'Prénom'),
            TextField::new('email', 'Email'),
            TextEditorField::new('contenu', 'Message'),
        ];
    }
    
}