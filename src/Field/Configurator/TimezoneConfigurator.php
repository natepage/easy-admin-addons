<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Field\Configurator;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use NatePage\EasyAdminAddons\Timezone\Resolver\TimezoneResolverInterface;

final readonly class TimezoneConfigurator implements FieldConfiguratorInterface
{
    public function __construct(
        private TimezoneResolverInterface $timezoneResolver,
    ) {
    }

    public function configure(FieldDto $field, EntityDto $entityDto, AdminContext $context): void
    {
        $field->setCustomOption(DateTimeField::OPTION_TIMEZONE, $this->timezoneResolver->resolveUserTimezone());

        $field->setFormTypeOptionIfNotSet('model_timezone', $this->timezoneResolver->resolveSystemTimezone());
        $field->setFormTypeOptionIfNotSet('view_timezone', $this->timezoneResolver->resolveUserTimezone());
    }

    public function supports(FieldDto $field, EntityDto $entityDto): bool
    {
        return $field->getFieldFqcn() === DateTimeField::class;
    }
}
