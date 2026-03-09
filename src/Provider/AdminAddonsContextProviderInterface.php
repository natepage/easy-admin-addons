<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Provider;

use NatePage\EasyAdminAddons\Context\AdminAddonsContextInterface;

interface AdminAddonsContextProviderInterface
{
    public function getAdminAddonsContext(): AdminAddonsContextInterface;

    public function setResolver(callable $resolver): void;
}
