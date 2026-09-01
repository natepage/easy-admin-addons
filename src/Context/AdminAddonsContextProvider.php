<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Context;

use NatePage\EasyAdminAddons\Twig\Resolver\TemplateResolverInterface;

final class AdminAddonsContextProvider implements AdminAddonsContextProviderInterface
{
    private ?AdminAddonsContextInterface $resolved = null;

    public function __construct(
        private readonly TemplateResolverInterface $templateResolver,
    ) {
    }

    /**
     * @var callable|null
     */
    private $resolver = null;

    public function getAdminAddonsContext(): AdminAddonsContextInterface
    {
        if (\is_callable($this->resolver) === false) {
            return AdminAddonsContext::create($this->templateResolver);
        }

        return $this->resolved ??= \call_user_func($this->resolver) ?? AdminAddonsContext::create($this->templateResolver);
    }

    public function setResolver(callable $resolver): void
    {
        $this->resolver = $resolver;
        $this->resolved = null;
    }
}
