<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Filter\Configurator;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use NatePage\EasyAdminAddons\Timezone\Resolver\TimezoneResolverInterface;

final readonly class TimezoneConfigurator implements FilterConfiguratorInterface
{
    public function __construct(
        private TimezoneResolverInterface $timezoneResolver,
    ) {
    }

    public function configure(FilterDto $filterDto, ?FieldDto $fieldDto, EntityDto $entityDto, AdminContext $context): void
    {
        $filterDto->setFormTypeOptionIfNotSet(
            'value_type_options.model_timezone',
            $this->timezoneResolver->resolveSystemTimezone()
        );

        $filterDto->setFormTypeOptionIfNotSet(
            'value_type_options.view_timezone',
            $this->timezoneResolver->resolveUserTimezone()
        );
    }

    public function supports(FilterDto $filterDto, ?FieldDto $fieldDto, EntityDto $entityDto, AdminContext $context): bool
    {
        return $filterDto->getFqcn() === DateTimeFilter::class && $fieldDto?->getFieldFqcn() === DateTimeField::class;
    }
}
