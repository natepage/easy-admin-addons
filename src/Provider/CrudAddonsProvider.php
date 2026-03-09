<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Provider;

use NatePage\EasyAdminAddons\Config\CrudAddons;

final class CrudAddonsProvider implements CrudAddonsProviderInterface
{
    private ?CrudAddons $resolved = null;

    /**
     * @var callable|null
     */
    private $resolver = null;

    public function getCrudAddons(): CrudAddons
    {
        if (\is_callable($this->resolver) === false) {
            return new CrudAddons();
        }

        return $this->resolved ??= \call_user_func($this->resolver) ?? new CrudAddons();
    }

    public function setResolver(callable $resolver): void
    {
        $this->resolver = $resolver;
        $this->resolved = null;
    }
}
