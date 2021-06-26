<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Contracts;

use DateTime;
use Inboxly\Receiver\Feed;

interface Fetcher
{
    /**
     * Get the unique key of the fetcher.
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Get an array of entry-correctors and feed-correctors.
     *
     * These correctors will be applied to each fetched feeds and entries
     *
     * @return array
     */
    public function getCorrectors(): array;

    /**
     * Make a feed Parameters object from an associative array
     *
     * @param array $parameters
     * @return \Inboxly\Receiver\Contracts\Parameters
     */
    public function makeParameters(array $parameters): Parameters;

    /**
     * Fetch feeds by parameters
     *
     * @param \Inboxly\Receiver\Contracts\Parameters $parameters
     * @param \DateTime|null $modifiedSince
     * @return \Inboxly\Receiver\Feed|null
     */
    public function fetch(Parameters $parameters, ?DateTime $modifiedSince = null): ?Feed;
}
