<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Tests\Stubs;

use Inboxly\Receiver\Contracts\UrlExplorer;

class StubUrlExplorer implements UrlExplorer
{
    public function getKey(): string
    {
        return 'stub-url';
    }

    public function canExploreByUrl(string $url): bool
    {
        return true;
    }

    public function exploreByUrl(string $url): array
    {
        return [];
    }

    public function isExclusive(): bool
    {
        return true;
    }
}
