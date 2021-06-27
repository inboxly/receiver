<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Tests\Stubs;

use Inboxly\Receiver\Contracts\QueryExplorer;
use Inboxly\Receiver\Contracts\UrlExplorer;

class StubUrlQueryExplorer implements UrlExplorer, QueryExplorer
{
    public function getKey(): string
    {
        return 'stub-url-query';
    }

    public function isExclusive(): bool
    {
        return true;
    }

    public function canExploreByUrl(string $url): bool
    {
        return true;
    }

    public function exploreByUrl(string $url): array
    {
        return [];
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
