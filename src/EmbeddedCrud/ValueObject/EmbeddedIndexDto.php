<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\EmbeddedCrud\ValueObject;

use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

final class EmbeddedIndexDto
{
    private ?Actions $actions = null;

    private null|array|string $defaultRowAction = null;

    private string $entityClass;

    private iterable $fields;

    /**
     * @var callable
     */
    private $paginatorCallback;

    private ?string $paginatorRouteName = null;

    private ?array $paginatorRouteParams = null;

    private ?bool $showEntityActionsAsDropdown = null;

    public function getActions(): ?Actions
    {
        return $this->actions;
    }

    public function setActions(?Actions $actions): EmbeddedIndexDto
    {
        $this->actions = $actions;
        return $this;
    }

    public function getDefaultRowAction(): array|string|null
    {
        return $this->defaultRowAction;
    }

    public function setDefaultRowAction(array|string|null $defaultRowAction): EmbeddedIndexDto
    {
        $this->defaultRowAction = $defaultRowAction;
        return $this;
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function setEntityClass(string $entityClass): EmbeddedIndexDto
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getFields(): iterable
    {
        return $this->fields;
    }

    public function setFields(iterable $fields): EmbeddedIndexDto
    {
        $this->fields = $fields;
        return $this;
    }

    public function getPaginatorCallback(): callable
    {
        return $this->paginatorCallback;
    }

    public function setPaginatorCallback(callable $paginatorCallback): EmbeddedIndexDto
    {
        $this->paginatorCallback = $paginatorCallback;
        return $this;
    }

    public function getPaginatorRouteName(): ?string
    {
        return $this->paginatorRouteName;
    }

    public function setPaginatorRouteName(?string $paginatorRouteName): EmbeddedIndexDto
    {
        $this->paginatorRouteName = $paginatorRouteName;
        return $this;
    }

    public function getPaginatorRouteParams(): ?array
    {
        return $this->paginatorRouteParams;
    }

    public function setPaginatorRouteParams(?array $paginatorRouteParams): EmbeddedIndexDto
    {
        $this->paginatorRouteParams = $paginatorRouteParams;
        return $this;
    }

    public function getShowEntityActionsAsDropdown(): ?bool
    {
        return $this->showEntityActionsAsDropdown;
    }

    public function setShowEntityActionsAsDropdown(?bool $showEntityActionsAsDropdown): EmbeddedIndexDto
    {
        $this->showEntityActionsAsDropdown = $showEntityActionsAsDropdown;
        return $this;
    }
}
