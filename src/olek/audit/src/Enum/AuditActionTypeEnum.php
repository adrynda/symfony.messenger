<?php

namespace Olek\Audit\Enum;

enum AuditActionTypeEnum: string
{
    case Update = 'update';

    case Delete = 'delete';
}
