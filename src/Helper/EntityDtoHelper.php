<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Helper;

use EasyCorp\Bundle\EasyAdminBundle\Collection\EntityCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Factory\ActionFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FieldFactory;

final readonly class EntityDtoHelper
{
    public function __construct(
        private ActionFactory $actionFactory,
        private EntityFactory $entityFactory,
        private FieldFactory $fieldFactory,
    ) {
    }

    public function createCollectionForInstancesAndFields(
        string $entityClass,
        iterable $instances,
        callable $fieldsFactory,
        ?callable $actionsFactory = null,
    ): EntityCollection {
        $entitiesCollection = $this->entityFactory->createCollection(
            $this->entityFactory->create($entityClass),
            $instances
        );

        $this->fieldFactory->processFieldsForAll(
            $entitiesCollection,
            new FieldCollection($fieldsFactory()),
            Crud::PAGE_DETAIL
        );

        $actions = Actions::new();

        if (\is_callable($actionsFactory)) {
            $actionsFactory($actions);
        }

        $this->actionFactory->processGlobalActionsAndEntityActionsForAll(
            $entitiesCollection,
            $actions->getAsDto(Crud::PAGE_DETAIL)
        );

        return $entitiesCollection;
    }
}
