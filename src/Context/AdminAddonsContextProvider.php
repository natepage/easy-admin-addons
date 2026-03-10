<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Context;

final class AdminAddonsContextProvider implements AdminAddonsContextProviderInterface
{
    private ?AdminAddonsContextInterface $resolved = null;

    /**
     * @var callable|null
     */
    private $resolver = null;

    public function getAdminAddonsContext(): AdminAddonsContextInterface
    {
        if (\is_callable($this->resolver) === false) {
            return AdminAddonsContext::create();
        }

        return $this->resolved ??= \call_user_func($this->resolver) ?? AdminAddonsContext::create();
    }

    public function setResolver(callable $resolver): void
    {
        $this->resolver = $resolver;
        $this->resolved = null;
    }
}
