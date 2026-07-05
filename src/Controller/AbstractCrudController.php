<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController as BaseAbstractCrudController;
use NatePage\EasyAdminAddons\Config\CrudAddons;
use NatePage\EasyAdminAddons\Context\AdminAddonsContextProviderInterface;
use NatePage\EasyAdminAddons\Twig\Resolver\TemplateResolverInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCrudController extends BaseAbstractCrudController
{
    private const array DEFAULT_ACTIONS_MAPPING = [
        Crud::PAGE_DETAIL => [
            Action::EDIT,
            Action::DELETE,
            Action::INDEX,
        ],
        Crud::PAGE_EDIT => [
            Action::SAVE_AND_RETURN,
            Action::SAVE_AND_CONTINUE,
        ],
        Crud::PAGE_INDEX => [
            Action::NEW,
            Action::EDIT,
            Action::DELETE,
        ],
        Crud::PAGE_NEW => [
            Action::SAVE_AND_RETURN,
            Action::SAVE_AND_ADD_ANOTHER,
        ],
    ];

    private AdminAddonsContextProviderInterface $adminAddonsContextProvider;

    private TemplateResolverInterface $templateResolver;

    #[Required]
    public function setAdminAddonsContextProvider(AdminAddonsContextProviderInterface $provider): void
    {
        $this->adminAddonsContextProvider = $provider;
    }

    #[Required]
    public function setTemplateResolver(TemplateResolverInterface $templateResolver): void
    {
        $this->templateResolver = $templateResolver;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);

        $crudAddons = $this->adminAddonsContextProvider->getAdminAddonsContext()->getCrudAddons();
        $actionsDto = $actions->getAsDto(Crud::PAGE_INDEX);

        if ($crudAddons->readOnly) {
            foreach (self::DEFAULT_ACTIONS_MAPPING as $pageName => $actionsList) {
                foreach ($actionsList as $actionName) {
                    // Keep index otherwise nothing works :)
                    if ($actionName === Action::INDEX) {
                        continue;
                    }

                    // Setting invalid permission so accessing the action URL directly does not work either
                    $actions->setPermission($actionName, 'easy_admin_addons_invalid_permission');

                    if (\is_string($pageName) && $actionsDto->getAction($pageName, $actionName) !== null) {
                        $actions->remove($pageName, $actionName);
                    }
                }
            }
        }

        if ($crudAddons->detailActionEnabled && $actionsDto->getAction(Crud::PAGE_INDEX, Action::DETAIL) === null) {
            $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
        }

        return $actions;
    }

    public function configureCrudAddons(CrudAddons $crudAddons): CrudAddons
    {
        return $crudAddons;
    }

    protected function renderTurboFrame(
        string $templatePath,
        ?array $templateContext = null,
        ?string $frameId = null
    ): Response {
        $frameTemplate = $this->templateResolver->resolvePath('turbo/frame.html.twig');

        return $this->render($frameTemplate, [
            'frameId' => $frameId,
            'templatePath' => $templatePath,
            'templateContext' => $templateContext,
        ]);
    }

    protected function resetActionPermissions(Actions $actions, string $actionName): void
    {
        $permissions = $actions->getAsDto(null)->getActionPermissions();

        unset($permissions[$actionName]);

        $actions->setPermissions($permissions);
    }
}
