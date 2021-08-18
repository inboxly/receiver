<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Sources\Rss;

use DateTime;
use FeedIo\Feed\ItemInterface;
use FeedIo\FeedIo;
use FeedIo\FeedIoException;
use FeedIo\Reader\Result;
use Inboxly\Receiver\Contracts\Fetcher;
use Inboxly\Receiver\Contracts\Parameters;
use Inboxly\Receiver\Entry;
use Inboxly\Receiver\Feed;

final class RssFetcher implements Fetcher
{
    /**
     * Unique key of the fetcher.
     */
    public const KEY = 'rss';

    /**
     * Array of correctors for feeds and entries
     *
     * @var array
     */
    protected array $correctors;

    /**
     * RssFetcher constructor.
     *
     * @param \FeedIo\FeedIo $feedIo RSS client instance
     * @param \Inboxly\Receiver\Sources\Rss\RssCorrector $rssCorrector
     */
    public function __construct(
        private FeedIo $feedIo,
        RssCorrector $rssCorrector
    ){
        $this->correctors[] = $rssCorrector;
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
        return $this->correctors;
    }

    /**
     * Make a feed Parameters object from an associative array
     *
     * @param array $parameters
     * @return \Inboxly\Receiver\Contracts\Parameters
     */
    public function makeParameters(array $parameters): Parameters
    {
        return new RssParameters($parameters['url']);
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
        try {
            $result = $this->feedIo->read($parameters->url, null, $modifiedSince);
        } catch (FeedIoException) {
            return null;
        }

        $feed = $this->mapToFeed($parameters, $result);

        $feed->entries = array_map(
            fn(ItemInterface $item) => $this->mapToEntry($item),
            iterator_to_array($result->getFeed()),
        );

        return $feed;
    }

    /**
     * Map RSS Client result to Feed instance
     *
     * @param \Inboxly\Receiver\Contracts\Parameters $parameters
     * @param \FeedIo\Reader\Result $result
     * @return \Inboxly\Receiver\Feed
     */
    protected function mapToFeed(Parameters $parameters, Result $result): Feed
    {
        $feedIoFeed = $result->getFeed();

        return new Feed(
            parameters: $parameters,
            name: $feedIoFeed->getTitle(),
            summary: $feedIoFeed->getDescription(),
            url: $feedIoFeed->getLink(),
            image: $feedIoFeed->getLogo(),
            authorName: $feedIoFeed->getAuthor()?->getName(),
            authorUrl: $feedIoFeed->getAuthor()?->getUri(),
            language: $feedIoFeed->getLanguage(),
            updatedAt: $feedIoFeed->getLastModified(),
            nextUpdateAt: $result->getNextUpdate(5 * 60),
        );
    }

    /**
     * Map RSS Client item to Entry instance
     *
     * @param \FeedIo\Feed\ItemInterface $feedIoItem
     * @return \Inboxly\Receiver\Entry
     */
    protected function mapToEntry(ItemInterface $feedIoItem): Entry
    {
        return new Entry(
            externalId: $feedIoItem->getPublicId(),
            name: $feedIoItem->getTitle(),
            summary: $feedIoItem->getSummary(),
            content: $feedIoItem->getContent(),
            url: $feedIoItem->getLink(),
            image: $this->getEntryImage($feedIoItem),
            authorName: $feedIoItem->getAuthor()?->getName(),
            authorUrl: $feedIoItem->getAuthor()?->getUri(),
            createdAt: $feedIoItem->getLastModified(),
            updatedAt: $feedIoItem->getLastModified(),
        );
    }

    /**
     * Get entry image from RSS Client item
     *
     * @param \FeedIo\Feed\ItemInterface $entry
     * @return string|null
     */
    protected function getEntryImage(ItemInterface $entry): ?string
    {
        $image = null;

        /** @var \FeedIo\Feed\Item\MediaInterface $media */
        foreach ($entry->getMedias() as $media) {
            if (str_starts_with((string)$media->getType(), 'image/') && ($image = $media->getUrl())) {
                break;
            } elseif ($image = $media->getThumbnail()) {
                break;
            }
        }

        return $image;
    }
}
