<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Twig\Resolver;

use function Symfony\Component\String\u;

final readonly class TemplateResolver implements TemplateResolverInterface
{
    public function resolvePath(string $template): string
    {
        return \sprintf('@EasyAdminAddons/%s', u($template)->ensureEnd('.html.twig'));
    }
}
