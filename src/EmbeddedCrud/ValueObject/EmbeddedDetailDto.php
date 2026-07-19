<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\EmbeddedCrud\ValueObject;

use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

final class EmbeddedDetailDto
{
    private ?Actions $actions = null;

    private object $entity;

    private iterable $fields;

    public function getActions(): ?Actions
    {
        return $this->actions;
    }

    public function setActions(?Actions $actions): EmbeddedDetailDto
    {
        $this->actions = $actions;
        return $this;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }

    public function setEntity(object $entity): EmbeddedDetailDto
    {
        $this->entity = $entity;
        return $this;
    }

    public function getFields(): iterable
    {
        return $this->fields;
    }

    public function setFields(iterable $fields): EmbeddedDetailDto
    {
        $this->fields = $fields;
        return $this;
    }
}
