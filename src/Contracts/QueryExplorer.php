<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Contracts;

interface QueryExplorer
{
    /**
     * Get the unique key of the explorer.
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Check that the explorer can be used for explore feed by specified query.
     *
     * @param string $query
     * @return bool
     */
    public function canExploreByQuery(string $query): bool;

    /**
     * Explore feed by specified query.
     *
     * @param string $query
     * @return array
     */
    public function exploreByQuery(string $query): array;

    /**
     * Flag indicating that the explorer should be used exclusively.
     *
     * If method return true then other explorers will not be used along with this
     *
     * @return bool
     */
    public function isExclusive(): bool;
}
