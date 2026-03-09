<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController as BaseAbstractCrudController;
use NatePage\EasyAdminAddons\Config\CrudAddons;

abstract class AbstractCrudController extends BaseAbstractCrudController
{
    public function configureCrudAddons(CrudAddons $crudAddons): CrudAddons
    {
        return $crudAddons;
    }
}
