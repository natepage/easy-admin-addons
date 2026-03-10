<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController as BaseAbstractDashboardController;
use NatePage\EasyAdminAddons\Config\CrudAddons;
use NatePage\EasyAdminAddons\Twig\Resolver\TemplateResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractDashboardController extends BaseAbstractDashboardController
{
    private const array OVERRIDE_TEMPLATES = [
        'crud/index' => 'crud/index',
    ];

    private TemplateResolverInterface $twigTemplateResolver;

    #[Required]
    public function setTwigTemplateResolver(TemplateResolverInterface $twigTemplateResolver): void
    {
        $this->twigTemplateResolver = $twigTemplateResolver;
    }

    public function configureCrud(): Crud
    {
        $crud = parent::configureCrud();

        foreach (self::OVERRIDE_TEMPLATES as $template => $override) {
            $crud->overrideTemplate($template, $this->twigTemplateResolver->resolvePath($override));
        }

        return $crud;
    }

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
