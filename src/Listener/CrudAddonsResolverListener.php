<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Listener;

use NatePage\EasyAdminAddons\Controller\AbstractCrudController;
use NatePage\EasyAdminAddons\Provider\CrudAddonsProviderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

final readonly class CrudAddonsResolverListener
{
    public function __construct(
        private CrudAddonsProviderInterface $crudAddonsProvider,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (\is_array($controller)) {
            $controller = $controller[0] ?? null;
        }

        if ($controller instanceof AbstractCrudController) {
            $this->crudAddonsProvider->setResolver(static function () use ($controller) {
                return $controller->configureCrudAddons();
            });

            return;
        }

        $this->logger->debug(\sprintf(
            'Controller "%s" is not an instance of "%s", skipping CRUD addons resolver setup.',
            \is_object($controller) ? $controller::class : 'not_object',
            AbstractCrudController::class
        ));
    }
}
