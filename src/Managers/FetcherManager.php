<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Managers;

use DateTime;
use Inboxly\Receiver\Contracts\EntryCorrector;
use Inboxly\Receiver\Contracts\FeedCorrector;
use Inboxly\Receiver\Contracts\Fetcher;
use Inboxly\Receiver\Contracts\Parameters;
use Inboxly\Receiver\Feed;
use Inboxly\Receiver\Sources\Common\CommonCorrector;

final class FetcherManager
{
    /**
     * @var \Inboxly\Receiver\Contracts\Fetcher[]
     */
    private array $fetchers;

    /**
     * FetcherManager constructor.
     */
    public function __construct(
        private CommonCorrector $corrector
    ){}

    /**
     * Add an instance of fetcher to the manager
     *
     * @param \Inboxly\Receiver\Contracts\Fetcher $fetcher
     */
    public function addFetcher(Fetcher $fetcher)
    {
        $this->fetchers[$fetcher->getKey()] = $fetcher;
    }

    /**
     * Get an instance of fetcher by key
     *
     * @param string $fetcherKey
     * @return \Inboxly\Receiver\Contracts\Fetcher|null
     */
    public function getFetcher(string $fetcherKey): ?Fetcher
    {
        return $this->fetchers[$fetcherKey] ?? null;
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
        $fetcherKey = $parameters->getFetcherKey();

        if (!$fetcher = $this->getFetcher($fetcherKey)) {
            return null;
        }

        $feed = $fetcher->fetch($parameters, $modifiedSince);

        if ($feed === null) {
            return null;
        }

        $this->correct($feed, $fetcher->getCorrectors());

        return $feed;
    }

    /**
     * Correct feed and entries using specified correctors
     *
     * @param \Inboxly\Receiver\Feed $feed
     * @param array $correctors
     */
    private function correct(Feed $feed, array $correctors): void
    {
        array_push($correctors, $this->corrector);

        foreach ($correctors as $corrector) {
            if ($corrector instanceof FeedCorrector) {
                $corrector->correctFeed($feed);
            }
            if ($corrector instanceof EntryCorrector) {
                foreach ($feed->entries as $entry) {
                    $corrector->correctEntry($entry);
                }
            }
        }
    }
}
