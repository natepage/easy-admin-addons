<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Field\Configurator;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use NatePage\EasyAdminAddons\Enum\FieldOption;

final readonly class TextTruncateMiddleConfigurator implements FieldConfiguratorInterface
{
    public function configure(FieldDto $field, EntityDto $entityDto, AdminContext $context): void
    {
        $value = $field->getValue();

        if ($value === null
            || $context->getCrud()->getCurrentAction() === Action::DETAIL
            || $field->getCustomOption(TextField::OPTION_RENDER_AS_HTML) === true
            || $field->getCustomOption(FieldOption::TruncateMiddle->value) !== true) {
            return;
        }

        // if it's an enum, transform it into its text form
        if ($value instanceof \UnitEnum) {
            $value = $value instanceof \BackedEnum ? $value->value : $value->name;
        }

        if (\is_string($value) === false && $value instanceof \Stringable === false) {
            throw new \RuntimeException(\sprintf(
                'The value of the "%s" field of the entity with ID = "%s" can\'t be converted into a string, so it cannot be represented by a TextField or a TextareaField.',
                $field->getProperty(),
                $entityDto->getPrimaryKeyValue()
            ));
        }

        $formattedValue = $field->getCustomOption(TextField::OPTION_STRIP_TAGS) === true
            ? \strip_tags((string) $value)
            : \htmlspecialchars((string) $value, \ENT_NOQUOTES, null, false);

        $formattedValue = $this->truncateMiddle($formattedValue, $field->getCustomOption(TextField::OPTION_MAX_LENGTH) ?? 64);

        $field->setFormattedValue($formattedValue);
    }

    public function supports(FieldDto $field, EntityDto $entityDto): bool
    {
        return \in_array($field->getFieldFqcn(), [TextField::class, TextareaField::class], true);
    }

    private function truncateMiddle(string $value, int $maxLength): string
    {
        if (\strlen($value) <= $maxLength) {
            return $value;
        }

        $ellipsis = '...';
        $halfLeft = (int) (\floor($maxLength / 2) - \strlen($ellipsis));
        $halfRight = (int) \ceil($maxLength / 2);

        return \sprintf(
            '%s%s%s',
            \substr($value, 0, $halfLeft),
            $ellipsis,
            \substr($value, -$halfRight)
        );
    }
}
