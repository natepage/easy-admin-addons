<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Config;

use NatePage\EasyAdminAddons\Enum\PersistenceDriver;

final class CrudAddons
{
    /**
     * A callback that will be called in the `getResults` method of the `EntityPaginator` class.
     * It should return an array of entities.
     *
     * @var callable|null
     */
    public $entityPaginatorGetResultsCallback = null;

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
}
