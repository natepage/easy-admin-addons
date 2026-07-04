<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Contracts\Translation\TranslatableInterface;

final readonly class TurboFrameField implements FieldInterface
{
    public const string OPTION_FRAME_ID = 'frame_id';

    public const string OPTION_FRAME_LAZY_LOADING = 'frame_lazy_loading';

    public const string OPTION_FRAME_URL = 'frame_url';

    public const string OPTION_PLACEHOLDER_TEMPLATE_PATH = 'placeholder_template_path';

    public const string OPTION_PLACEHOLDER_TEMPLATE_CONTEXT = 'placeholder_template_context';

    public const string OPTION_ROUTE_NAME = 'route_name';

    public const string OPTION_ROUTE_PARAMS = 'route_params';

    use FieldTrait;

    public static function new(string $propertyName, TranslatableInterface|bool|string|null $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel(false)
            ->setTemplatePath('@EasyAdminAddons/crud/field/turbo_frame.html.twig')
            ->setVirtual(true);
    }

    public function setFrameId(callable|string $frameId): self
    {
        $this->dto->setCustomOption(self::OPTION_FRAME_ID, $frameId);

        return $this;
    }

    public function setFrameUrl(callable|string $frameUrl): self
    {
        $this->dto->setCustomOption(self::OPTION_FRAME_URL, $frameUrl);

        return $this;
    }

    public function setLazyLoading(bool $lazyLoading): self
    {
        $this->dto->setCustomOption(self::OPTION_FRAME_LAZY_LOADING, $lazyLoading);

        return $this;
    }

    public function setPlaceholderTemplatePath(callable|string $placeholderTemplatePath): self
    {
        $this->dto->setCustomOption(self::OPTION_PLACEHOLDER_TEMPLATE_PATH, $placeholderTemplatePath);

        return $this;
    }

    public function setPlaceholderTemplateContext(callable|string $placeholderTemplateContext): self
    {
        $this->dto->setCustomOption(self::OPTION_PLACEHOLDER_TEMPLATE_CONTEXT, $placeholderTemplateContext);

        return $this;
    }

    public function setRouteName(callable|string $routeName): self
    {
        $this->dto->setCustomOption(self::OPTION_ROUTE_NAME, $routeName);

        return $this;
    }

    public function setRouteParams(callable|array $routeParams): self
    {
        $this->dto->setCustomOption(self::OPTION_ROUTE_PARAMS, $routeParams);

        return $this;
    }
}
