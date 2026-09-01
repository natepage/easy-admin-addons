<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Context;

use NatePage\EasyAdminAddons\Config\CrudAddons;
use NatePage\EasyAdminAddons\Session\FlashBagManager;
use NatePage\EasyAdminAddons\Twig\Resolver\TemplateResolverInterface;
use NatePage\EasyAdminAddons\Twig\ValueObject\Alert;

final class AdminAddonsContext implements AdminAddonsContextInterface
{
    public function __construct(
        private readonly TemplateResolverInterface $templateResolver,
        private ?CrudAddons $crudAddons = null,
        private ?FlashBagManager $flashBagManager = null,
    ) {
    }

    public static function create(TemplateResolverInterface $templateResolver): self
    {
        return new self($templateResolver);
    }

    public function addContentAlert(Alert $alert): void
    {
        $this->flashBagManager?->addContentAlert($alert);
    }

    public function getContentAlerts(): array
    {
        return $this->flashBagManager?->getContentAlerts() ?? [];
    }

    public function getCrudAddons(): CrudAddons
    {
        return $this->crudAddons ??= new CrudAddons();
    }

    public function setCrudAddons(?CrudAddons $crudAddons): self
    {
        $this->crudAddons = $crudAddons;

        return $this;
    }

    public function setFlashBagManager(?FlashBagManager $flashBagManager): self
    {
        $this->flashBagManager = $flashBagManager;

        return $this;
    }

    public function getTemplatePath(string $templateName): string
    {
        $crudAddons = $this->getCrudAddons();

        if (isset($crudAddons->overriddenTemplates[$templateName])) {
            return $crudAddons->overriddenTemplates[$templateName];
        }

        return $this->templateResolver->resolvePath($templateName);
    }
}
