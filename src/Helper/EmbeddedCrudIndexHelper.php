<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Helper;

use EasyCorp\Bundle\EasyAdminBundle\Collection\EntityCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Provider\AdminContextProviderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Factory\ActionFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FieldFactory;
use NatePage\EasyAdminAddons\Twig\Resolver\TemplateResolverInterface;

final readonly class EmbeddedCrudIndexHelper
{
    public function __construct(
        private ActionFactory $actionFactory,
        private AdminContextProviderInterface $adminContextProvider,
        private EntityFactory $entityFactory,
        private FieldFactory $fieldFactory,
        private TemplateResolverInterface $templateResolver,
    ) {
    }

    public function configureEntityCollection(
        string $entityClass,
        iterable $instances,
        iterable $fields,
        ?Actions $actions = null,
        null|array|string $defaultRowAction = null,
        ?bool $showEntityActionsAsDropdown = null
    ): EntityCollection {
        $context = $this->adminContextProvider->getContext();

        // In order for field configurators to work as expected we must fake the current page to be the index or detail.
        $context->getCrud()?->setPageName(Crud::PAGE_INDEX);
        $context->getCrud()?->setShowEntityActionsAsDropdown($showEntityActionsAsDropdown ?? true);

        if ($defaultRowAction) {
            $context->getCrud()?->setDefaultRowAction($defaultRowAction);
        }

        $entitiesCollection = $this->entityFactory->createCollection(
            $this->entityFactory->create($entityClass),
            $instances
        );

        $this->fieldFactory->processFieldsForAll(
            $entitiesCollection,
            new FieldCollection($fields),
            Crud::PAGE_INDEX
        );

        if ($actions) {
            $this->actionFactory->processGlobalActionsAndEntityActionsForAll(
                $entitiesCollection,
                $actions->getAsDto(Crud::PAGE_INDEX)
            );
        }

        return $entitiesCollection;
    }

    public function getTemplatePath(): string
    {
        return $this->templateResolver->resolvePath('crud/field/embedded_crud_index.html.twig');
    }
}
