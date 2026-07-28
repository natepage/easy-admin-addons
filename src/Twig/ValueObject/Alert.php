<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Twig\ValueObject;

final readonly class Alert
{
    public function __construct(
        public string $message,
        public string $variant = 'info',
        public ?string $icon = null,
        public ?string $title = null,
        public ?bool $withDismissButton = false,
        public string $translationDomain = 'messages',
    ) {
    }
}
