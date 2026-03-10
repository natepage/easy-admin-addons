<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Context;

interface AdminAddonsContextProviderInterface
{
    public function getAdminAddonsContext(): AdminAddonsContextInterface;

    public function setResolver(callable $resolver): void;
}
