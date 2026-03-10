<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Timezone\Resolver;

final readonly class TimezoneResolver implements TimezoneResolverInterface
{
    public function __construct(
        private ?string $systemTimezone = null,
        private ?string $userTimezone = null,
    ) {
    }

    public function resolveSystemTimezone(): string
    {
        return $this->systemTimezone ?? 'UTC';
    }

    public function resolveUserTimezone(): string
    {
        return $this->userTimezone ?? \date_default_timezone_get();
    }
}
