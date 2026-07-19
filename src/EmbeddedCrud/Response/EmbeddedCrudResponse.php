<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\EmbeddedCrud\Response;

final readonly class EmbeddedCrudResponse
{
    public function __construct(
        private string $view,
        private array $parameters,
    ) {
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getView(): string
    {
        return $this->view;
    }
}
