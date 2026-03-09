<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Context;

use NatePage\EasyAdminAddons\Config\CrudAddons;

interface AdminAddonsContextInterface
{
    public function getCrudAddons(): CrudAddons;
}
