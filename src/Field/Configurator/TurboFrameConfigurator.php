<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Field\Configurator;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use NatePage\EasyAdminAddons\Field\TurboFrameField;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class TurboFrameConfigurator implements FieldConfiguratorInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function configure(FieldDto $field, EntityDto $entityDto, AdminContext $context): void
    {
        $this->setFrameId($field, $entityDto);
        $this->setFrameUrl($field, $entityDto);
        $this->setPlaceholderTemplate($field, $entityDto);
    }

    public function supports(FieldDto $field, EntityDto $entityDto): bool
    {
        return $field->getFieldFqcn() === TurboFrameField::class;
    }

    private function getCustomOptionValue(string $option, FieldDto $field, EntityDto $entityDto): mixed
    {
        $value = $field->getCustomOption($option);

        return \is_callable($value) ? $value($entityDto->getInstance()) : $value;
    }

    private function setFrameId(FieldDto $field, EntityDto $entityDto): void
    {
        $frameId = $this->getCustomOptionValue(TurboFrameField::OPTION_FRAME_ID, $field, $entityDto);

        if (\is_string($frameId) === false || $frameId === '') {
            throw new \InvalidArgumentException(sprintf(
                'The "%s" option must be a non-empty string or a callable that returns a non-empty string.',
                TurboFrameField::OPTION_FRAME_ID
            ));
        }

        $field->setCustomOption(TurboFrameField::OPTION_FRAME_ID, $frameId);
    }

    private function setFrameUrl(FieldDto $field, EntityDto $entityDto): void
    {
        $frameUrl = $this->getCustomOptionValue(TurboFrameField::OPTION_FRAME_URL, $field, $entityDto);

        if (\is_string($frameUrl) && $frameUrl !== '') {
            $field->setCustomOption(TurboFrameField::OPTION_FRAME_URL, $frameUrl);

            return;
        }

        $routeName = $this->getCustomOptionValue(TurboFrameField::OPTION_ROUTE_NAME, $field, $entityDto);
        $routeParams = $this->getCustomOptionValue(TurboFrameField::OPTION_ROUTE_PARAMS, $field, $entityDto);

        if (\is_string($routeName) === false || $routeName === '') {
            return;
        }

        $frameUrl = $this->urlGenerator->generate($routeName, $routeParams ?? []);

        $field->setCustomOption(TurboFrameField::OPTION_FRAME_URL, $frameUrl);
    }

    private function setPlaceholderTemplate(FieldDto $field, EntityDto $entityDto): void
    {
        $placeholderTemplatePath = $this->getCustomOptionValue(
            TurboFrameField::OPTION_PLACEHOLDER_TEMPLATE_PATH,
            $field,
            $entityDto
        );

        if (\is_string($placeholderTemplatePath) === false || $placeholderTemplatePath === '') {
            $placeholderTemplatePath = null;
        }

        $placeholderTemplateContext = $this->getCustomOptionValue(
            TurboFrameField::OPTION_PLACEHOLDER_TEMPLATE_CONTEXT,
            $field,
            $entityDto
        );

        if (\is_array($placeholderTemplateContext) === false) {
            $placeholderTemplateContext = [];
        }

        $field->setCustomOption(TurboFrameField::OPTION_PLACEHOLDER_TEMPLATE_PATH, $placeholderTemplatePath);
        $field->setCustomOption(TurboFrameField::OPTION_PLACEHOLDER_TEMPLATE_CONTEXT, $placeholderTemplateContext);
    }
}
