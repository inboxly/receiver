<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Contracts;

interface UrlExplorer
{
    /**
     * Get the unique key of the explorer.
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Check that the explorer can be used for explore feed by specified url.
     *
     * @param string $url
     * @return bool
     */
    public function canExploreByUrl(string $url): bool;

    /**
     * Explore feed by specified url.
     *
     * @param string $url
     * @return array
     */
    public function exploreByUrl(string $url): array;

    /**
     * Flag indicating that the explorer should be used exclusively.
     *
     * If method return true then other explorers will not be used along with this
     *
     * @return bool
     */
    public function isExclusive(): bool;
}
