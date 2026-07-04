<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Field\Configurator;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use NatePage\EasyAdminAddons\Field\EmbeddedCrudIndexField;
use NatePage\EasyAdminAddons\Helper\EntityDtoHelper;
use NatePage\EasyAdminAddons\Twig\Resolver\TemplateResolverInterface;

final readonly class EmbeddedCrudIndexConfigurator implements FieldConfiguratorInterface
{
    public function __construct(
        private EntityDtoHelper $entityDtoHelper,
        private TemplateResolverInterface $templateResolver,
    ) {
    }

    public function configure(FieldDto $field, EntityDto $entityDto, AdminContext $context): void
    {
        // Replace templatePath only if field is using default one from EasyAdminBundle itself
        $templatePath = $field->getTemplatePath();
        if (\is_string($templatePath) && \str_starts_with($templatePath, '@EasyAdmin/')) {
            $field->setTemplatePath($this->templateResolver->resolvePath('crud/field/embedded_crud_index'));
        }

        $entityClass = $field->getCustomOption(EmbeddedCrudIndexField::OPTION_ENTITY_CLASS);
        $entitiesCallable = $field->getCustomOption(EmbeddedCrudIndexField::OPTION_ENTITIES_CALLABLE);
        $fieldsCallable = $field->getCustomOption(EmbeddedCrudIndexField::OPTION_FIELDS_CALLABLE);
        $actionsCallable = $field->getCustomOption(EmbeddedCrudIndexField::OPTION_ACTIONS_CALLABLE);

        $entities = $this->entityDtoHelper->createCollectionForInstancesAndFields(
            $entityClass,
            $entitiesCallable($entityDto->getInstance()),
            $fieldsCallable,
            $actionsCallable,
        );

        $field->setValue($entities);
    }

    public function supports(FieldDto $field, EntityDto $entityDto): bool
    {
        return $field->getFieldFqcn() === EmbeddedCrudIndexField::class;
    }
}
