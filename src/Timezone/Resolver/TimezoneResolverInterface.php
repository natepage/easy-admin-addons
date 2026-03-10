<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Timezone\Resolver;

interface TimezoneResolverInterface
{
    public function resolveSystemTimezone(): string;

    public function resolveUserTimezone(): string;
}
