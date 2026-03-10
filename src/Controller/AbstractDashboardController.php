<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController as BaseAbstractDashboardController;
use NatePage\EasyAdminAddons\Config\CrudAddons;

abstract class AbstractDashboardController extends BaseAbstractDashboardController
{
    public function configureCrudAddons(): CrudAddons
    {
        return new CrudAddons();
    }

    public function configureDashboard(): Dashboard
    {
        return parent::configureDashboard()
            ->renderContentMaximized();
    }
}
