<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Listener;

use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Factory\ControllerFactory;
use NatePage\EasyAdminAddons\Config\CrudAddons;
use NatePage\EasyAdminAddons\Context\AdminAddonsContext;
use NatePage\EasyAdminAddons\Context\AdminAddonsContextInterface;
use NatePage\EasyAdminAddons\Context\AdminAddonsContextProviderInterface;
use NatePage\EasyAdminAddons\Controller\AbstractCrudController;
use NatePage\EasyAdminAddons\Controller\AbstractDashboardController;
use NatePage\EasyAdminAddons\Session\FlashBagManager;
use NatePage\EasyAdminAddons\Twig\Resolver\TemplateResolverInterface;
use NatePage\SymfonySecurity\OAuth\User\OAuthUserInterface;
use NatePage\Utils\Helper\StringHelper;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;

final readonly class AdminAddonsContextResolverListener
{
    public function __construct(
        private ControllerFactory $controllerFactory,
        private AdminAddonsContextProviderInterface $adminAddonsContextProvider,
        private FlashBagManager $flashBagManager,
        private LoggerInterface $logger,
        private Security $security,
        private TemplateResolverInterface $templateResolver,
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Resolver controller in the closure to avoid unnecessary controller instantiation if no CRUD addons are needed
        $resolver = function () use ($request): AdminAddonsContextInterface {
            $context = AdminAddonsContext::create($this->templateResolver);
            $context->setCrudAddons($this->resolveCrudAddons($request));
            $context->setFlashBagManager($this->flashBagManager);

            // Handle user impersonation, only if not already set so applications can control the logic
            if ($context->getCrudAddons()->userImpersonator === null) {
                $user = $this->security->getUser();
                if (\interface_exists(OAuthUserInterface::class)
                    && $user instanceof OAuthUserInterface
                    && $user->isImpersonated()) {
                    $context->getCrudAddons()->isUserImpersonated = true;
                    $context->getCrudAddons()->userImpersonator = $user->getImpersonator();
                }

                $token = $this->security->getToken();
                if ($this->security->isGranted(AuthenticatedVoter::IS_IMPERSONATOR)
                    && $token instanceof SwitchUserToken) {
                    $context->getCrudAddons()->isUserImpersonated = true;
                    $context->getCrudAddons()->userImpersonator = $token->getUserIdentifier();
                }
            }

            return $context;
        };

        $this->adminAddonsContextProvider->setResolver($resolver);
    }

    private function resolveCrudAddons(Request $request): ?CrudAddons
    {
        $dashboardFqcn = $request->attributes->get(EA::DASHBOARD_CONTROLLER_FQCN);
        $controllerFqcn = $request->attributes->get(EA::CRUD_CONTROLLER_FQCN);
        $actionName = $request->attributes->get(EA::CRUD_ACTION);

        if (StringHelper::isEmpty($dashboardFqcn) || StringHelper::isEmpty($controllerFqcn)) {
            $this->logger->debug('Not an easy-admin route, skipping CRUD addons resolver');

            return null;
        }

        $dashboardController = $this->controllerFactory->getDashboardControllerInstance($dashboardFqcn, $request);
        $crudController = $this->controllerFactory->getCrudControllerInstance($controllerFqcn, $actionName, $request);

        $crudAddons = $dashboardController instanceof AbstractDashboardController
            ? $dashboardController->configureCrudAddons()
            : new CrudAddons();

        if ($crudController instanceof AbstractCrudController) {
            $crudAddons = $crudController->configureCrudAddons($crudAddons);
        }

        return $crudAddons;
    }
}
