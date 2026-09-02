<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Config;

use NatePage\EasyAdminAddons\Enum\PersistenceDriver;

final class CrudAddons
{
    public bool $detailActionEnabled = false;

    public bool $isUserImpersonated = false;

    /**
     * A callback that will be called to create the ObjectRepositoryInterface instance, instead of resolving it
     * from the registry.
     *
     * @var callable|null
     */
    public $entityPaginatorRepositoryFactory = null;

    /**
     * A callback that will be called in the `getResults` method of the `EntityPaginator` class.
     * It should return an array of entities.
     *
     * @var callable|null
     */
    public $entityPaginatorGetResultsCallback = null;

    public ?string $entityPaginatorObjectClass = null;

    public ?string $entityPaginatorRouteName = null;

    public ?array $entityPaginatorRouteParams = null;

    public string|PersistenceDriver $persistenceDriver = PersistenceDriver::Default {
        get {
            return $this->persistenceDriver instanceof PersistenceDriver
                ? $this->persistenceDriver->value
                : $this->persistenceDriver;
        }
        set {
            $this->persistenceDriver = $value;
        }
    }

    public array $overriddenIncludesTemplates = [];

    public bool $readOnly = false;

    public bool $renderTablesInCard = false;

    public ?string $userImpersonator = null;

    public function overrideIncludeTemplate(string $templateName, string $templatePath): void
    {
        $this->overriddenIncludesTemplates[$templateName] = $templatePath;
    }
}
