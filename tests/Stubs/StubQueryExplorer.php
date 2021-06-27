<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Tests\Stubs;

use Inboxly\Receiver\Contracts\QueryExplorer;

class StubQueryExplorer implements QueryExplorer
{
    public function getKey(): string
    {
        return 'stub-query';
    }

    public function isExclusive(): bool
    {
        return true;
    }

    public function canExploreByQuery(string $query): bool
    {
        return true;
    }

    public function exploreByQuery(string $query): array
    {
        return [];
    }
}
