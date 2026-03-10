<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Twig\Resolver;

interface TemplateResolverInterface
{
    public function resolvePath(string $template): string;
}
