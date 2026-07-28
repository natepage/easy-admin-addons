<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Context;

use NatePage\EasyAdminAddons\Config\CrudAddons;
use NatePage\EasyAdminAddons\Session\FlashBagManager;
use NatePage\EasyAdminAddons\Twig\ValueObject\Alert;

final class AdminAddonsContext implements AdminAddonsContextInterface
{
    public function __construct(
        private ?CrudAddons $crudAddons = null,
        private ?FlashBagManager $flashBagManager = null,
    ) {
    }

    public static function create(): self
    {
        return new self();
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
}
