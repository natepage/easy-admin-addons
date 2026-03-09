<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Config;

use NatePage\EasyAdminAddons\Enum\PersistenceDriver;

final class CrudAddons
{
    public PersistenceDriver $persistenceDriver = PersistenceDriver::Default;
}
