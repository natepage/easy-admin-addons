<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Bundle\Enum;

enum ConfigParam: string
{
    case TimezoneSystemTimezone = 'easy_admin_addons.timezone.system_timezone';

    case TimezoneUserTimezone = 'easy_admin_addons.timezone.user_timezone';
}
