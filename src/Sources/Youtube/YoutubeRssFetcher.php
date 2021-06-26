<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Sources\Youtube;

use DateTime;
use Inboxly\Receiver\Contracts\Fetcher;
use Inboxly\Receiver\Contracts\Parameters;
use Inboxly\Receiver\Feed;
use Inboxly\Receiver\Sources\Rss\RssFetcher;

final class YoutubeRssFetcher implements Fetcher
{
    /**
     * Unique key of the fetcher.
     */
    public const KEY = 'youtube_rss';

    /**
     * Base fetcher instance
     *
     * @var \Inboxly\Receiver\Sources\Rss\RssFetcher
     */
    private RssFetcher $rssFetcher;

    /**
     * YoutubeRssFetcher constructor.
     *
     * @param \Inboxly\Receiver\Sources\Rss\RssFetcher $rssFetcher
     */
    public function __construct(RssFetcher $rssFetcher)
    {
        $this->rssFetcher = $rssFetcher;
    }

    /**
     * Get the unique key of the fetcher.
     *
     * @return string
     */
    public function getKey(): string
    {
        return self::KEY;
    }

    /**
     * Get an array of entry-correctors and feed-correctors.
     *
     * These correctors will be applied to each fetched feeds and entries
     *
     * @return array
     */
    public function getCorrectors(): array
    {
        return $this->rssFetcher->getCorrectors();
    }

    /**
     * Make a feed Parameters object from an associative array
     *
     * @param array $parameters
     * @return \Inboxly\Receiver\Contracts\Parameters
     */
    public function makeParameters(array $parameters): Parameters
    {
        $rssParameters = $this->rssFetcher->makeParameters($parameters);
        $rssParameters->setFetcherKey($this->getKey());

        return $rssParameters;
    }

    /**
     * Fetch feeds by parameters
     *
     * @param \Inboxly\Receiver\Contracts\Parameters $parameters
     * @param \DateTime|null $modifiedSince
     * @return \Inboxly\Receiver\Feed|null
     */
    public function fetch(Parameters $parameters, ?DateTime $modifiedSince = null): ?Feed
    {
        return $this->rssFetcher->fetch($parameters, $modifiedSince);
    }
}
