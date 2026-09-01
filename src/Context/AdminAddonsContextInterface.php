<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Context;

use NatePage\EasyAdminAddons\Config\CrudAddons;
use NatePage\EasyAdminAddons\Twig\ValueObject\Alert;

interface AdminAddonsContextInterface
{
    public function addContentAlert(Alert $alert): void;

    /**
     * @return \NatePage\EasyAdminAddons\Twig\ValueObject\Alert[]
     */
    public function getContentAlerts(): array;

    public function getCrudAddons(): CrudAddons;

    public function getTemplatePath(string $templateName): string;
}
