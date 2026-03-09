<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Persistence;

use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Orm\EntityPaginatorInterface;

interface PersistenceDriverRegistryInterface
{
    public function getEntityPaginator(): EntityPaginatorInterface;

    public function getManagerRegistry(): ManagerRegistry;
}
