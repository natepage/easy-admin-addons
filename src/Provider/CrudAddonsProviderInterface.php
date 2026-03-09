<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Provider;

use NatePage\EasyAdminAddons\Config\CrudAddons;

interface CrudAddonsProviderInterface
{
    public function getCrudAddons(): CrudAddons;

    public function setResolver(callable $resolver): void;
}
