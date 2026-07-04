<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Contracts\Translation\TranslatableInterface;

final class EmbeddedCrudIndexField implements FieldInterface
{
    public const string OPTION_ACTIONS_CALLABLE = 'actionsCallable';

    public const string OPTION_ENTITY_CLASS = 'entityClass';

    public const string OPTION_ENTITIES_CALLABLE = 'entitiesCallable';

    public const string OPTION_FIELDS_CALLABLE = 'fieldsCallable';

    use FieldTrait;

    public static function new(string $propertyName, TranslatableInterface|bool|string|null $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel(false)
            ->setVirtual(true);
    }

    public function setActionsCallable(callable $actionsCallable): self
    {
        $this->setCustomOption(self::OPTION_ACTIONS_CALLABLE, $actionsCallable);

        return $this;
    }

    public function setEntityClass(string $entityClass): self
    {
        $this->setCustomOption(self::OPTION_ENTITY_CLASS, $entityClass);

        return $this;
    }

    public function setEntitiesCallable(callable $entitiesCallable): self
    {
        $this->setCustomOption(self::OPTION_ENTITIES_CALLABLE, $entitiesCallable);

        return $this;
    }

    public function setFieldsCallable(callable $fieldsCallable): self
    {
        $this->setCustomOption(self::OPTION_FIELDS_CALLABLE, $fieldsCallable);

        return $this;
    }
}
