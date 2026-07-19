<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\EmbeddedCrud\Factory;

use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Orm\EntityPaginatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Provider\AdminContextProviderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Factory\ActionFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FieldFactory;
use NatePage\EasyAdminAddons\Context\AdminAddonsContextProviderInterface;
use NatePage\EasyAdminAddons\EmbeddedCrud\Response\EmbeddedCrudResponse;
use NatePage\EasyAdminAddons\EmbeddedCrud\ValueObject\EmbeddedDetailDto;
use NatePage\EasyAdminAddons\EmbeddedCrud\ValueObject\EmbeddedIndexDto;
use NatePage\EasyAdminAddons\Twig\Resolver\TemplateResolverInterface;

final readonly class EmbeddedCrudResponseFactory
{
    public function __construct(
        private ActionFactory $actionFactory,
        private AdminContextProviderInterface $adminContextProvider,
        private AdminAddonsContextProviderInterface $adminAddonsContextProvider,
        private EntityFactory $entityFactory,
        private EntityPaginatorInterface $entityPaginator,
        private FieldFactory $fieldFactory,
        private TemplateResolverInterface $templateResolver,
    ) {
    }

    public function createDetailResponse(EmbeddedDetailDto $embeddedDetailDto): EmbeddedCrudResponse
    {
        $view = $this->templateResolver->resolvePath('crud/field/embedded_crud_detail.html.twig');

        $context = $this->adminContextProvider->getContext();
        $context->getCrud()?->setPageName(Crud::PAGE_DETAIL);

        $entityDto = $this->entityFactory->createForEntityInstance($embeddedDetailDto->getEntity());

        $this->fieldFactory->processFields(
            $entityDto,
            new FieldCollection($embeddedDetailDto->getFields()),
            Crud::PAGE_DETAIL
        );

        if ($embeddedDetailDto->getActions()) {
            $this->actionFactory->processEntityActions(
                $entityDto,
                $embeddedDetailDto->getActions()->getAsDto(Crud::PAGE_DETAIL)
            );
        }

        return new EmbeddedCrudResponse($view, [
            'entity' => $entityDto,
        ]);
    }

    public function createIndexResponse(EmbeddedIndexDto $embeddedIndexDto): EmbeddedCrudResponse
    {
        $view = $this->templateResolver->resolvePath('crud/field/embedded_crud_index.html.twig');

        $context = $this->adminContextProvider->getContext();
        $addonsContext = $this->adminAddonsContextProvider->getAdminAddonsContext();

        // In order for field configurators to work as expected we must fake the current page to be the index.
        $context->getCrud()?->setPageName(Crud::PAGE_INDEX);
        $context->getCrud()?->setShowEntityActionsAsDropdown($embeddedIndexDto->getShowEntityActionsAsDropdown() ?? true);

        if ($embeddedIndexDto->getDefaultRowAction()) {
            $context->getCrud()?->setDefaultRowAction($embeddedIndexDto->getDefaultRowAction());
        }

        $addonsContext->getCrudAddons()->entityPaginatorObjectClass = $embeddedIndexDto->getEntityClass();
        $addonsContext->getCrudAddons()->entityPaginatorGetResultsCallback = $embeddedIndexDto->getPaginatorCallback();

        $entitiesCollection = $this->entityFactory->createCollection(
            $this->entityFactory->create($embeddedIndexDto->getEntityClass()),
            $this->entityPaginator->getResults()
        );

        $this->fieldFactory->processFieldsForAll(
            $entitiesCollection,
            new FieldCollection($embeddedIndexDto->getFields()),
            Crud::PAGE_INDEX
        );

        if ($embeddedIndexDto->getActions()) {
            $this->actionFactory->processGlobalActionsAndEntityActionsForAll(
                $entitiesCollection,
                $embeddedIndexDto->getActions()->getAsDto(Crud::PAGE_INDEX)
            );
        }

        return new EmbeddedCrudResponse($view, [
            'entities' => $entitiesCollection,
            'paginator' => $this->entityPaginator,
        ]);
    }
}
