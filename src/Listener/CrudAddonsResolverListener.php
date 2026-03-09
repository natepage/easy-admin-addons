<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Listener;

use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Factory\ControllerFactory;
use NatePage\EasyAdminAddons\Config\CrudAddons;
use NatePage\EasyAdminAddons\Controller\AbstractCrudController;
use NatePage\EasyAdminAddons\Provider\CrudAddonsProviderInterface;
use NatePage\Utils\Helper\StringHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final readonly class CrudAddonsResolverListener
{
    public function __construct(
        private ControllerFactory $controllerFactory,
        private CrudAddonsProviderInterface $crudAddonsProvider,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $controllerFqcn = $request->attributes->get(EA::CRUD_CONTROLLER_FQCN);
        $actionName = $request->attributes->get(EA::CRUD_ACTION);

        if (StringHelper::isEmpty($controllerFqcn)) {
            $this->logger->debug('Not an easy-admin route, skipping CRUD addons resolver');

            return;
        }

        // Resolver controller in the closure to avoid unnecessary controller instantiation if no CRUD addons are needed
        $this->crudAddonsProvider->setResolver(function () use ($controllerFqcn, $actionName, $request): CrudAddons {
            $controller = $this->controllerFactory->getCrudControllerInstance($controllerFqcn, $actionName, $request);

            return $controller instanceof AbstractCrudController
                ? $controller->configureCrudAddons()
                : new CrudAddons();
        });
    }
}
