<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Twig\Extension;

use NatePage\EasyAdminAddons\Provider\AdminAddonsContextProviderInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AdminAddonsContextExtension extends AbstractExtension
{
    public function __construct(
        private readonly AdminAddonsContextProviderInterface $adminAddonsContextProvider,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('adminAddonsContext', [$this->adminAddonsContextProvider, 'getAdminAddonsContext']),
        ];
    }
}
