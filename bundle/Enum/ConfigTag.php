<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Bundle\Enum;

enum ConfigTag: string
{
    case PersistenceDriverEntityPaginator = 'easy_admin_addons.persistence_driver.entity_paginator';

    case PersistenceDriverManagerRegistry = 'easy_admin_addons.persistence_driver.manager_registry';
}
