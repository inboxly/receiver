<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Sources\Rss;

use FeedIo\FeedIo;
use Inboxly\Receiver\Contracts\UrlExplorer;

final class RssExplorer implements UrlExplorer
{
    /**
     * RssExplorer constructor.
     *
     * @param \FeedIo\FeedIo $feedIo
     */
    public function __construct(
        private FeedIo $feedIo
    ){}

    /**
     * Get the unique key of the explorer.
     *
     * @return string
     */
    public function getKey(): string
    {
        return 'rss';
    }

    /**
     * Check that the explorer can be used for explore feed by specified url.
     *
     * @param string $url
     * @return bool
     */
    public function canExploreByUrl(string $url): bool
    {
        return true;
    }

    /**
     * Explore feed by specified url.
     *
     * @param string $url
     * @return array
     */
    public function exploreByUrl(string $url): array
    {
        $feedUrls = $this->feedIo->discover($url);

        return array_map(
            fn(string $url) => new RssParameters($url),
            $feedUrls
        );
    }

    /**
     * Flag indicating that the explorer should be used exclusively.
     *
     * If method return true then other explorers will not be used along with this
     *
     * @return bool
     */
    public function isExclusive(): bool
    {
        return false;
    }
}
