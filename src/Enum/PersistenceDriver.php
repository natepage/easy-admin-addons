<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Enum;

enum PersistenceDriver: string
{
    case Default = 'default';
    case DynamoDb = 'dynamo_db';
}
