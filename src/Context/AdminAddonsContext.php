<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Context;

use NatePage\EasyAdminAddons\Config\CrudAddons;

final class AdminAddonsContext implements AdminAddonsContextInterface
{
    public function __construct(
        private ?CrudAddons $crudAddons = null
    ) {
    }

    public static function create(): self
    {
        return new self();
    }

    public function getCrudAddons(): CrudAddons
    {
        return $this->crudAddons ??= new CrudAddons();
    }

    public function setCrudAddons(?CrudAddons $crudAddons): self
    {
        $this->crudAddons = $crudAddons;

        return $this;
    }
}
