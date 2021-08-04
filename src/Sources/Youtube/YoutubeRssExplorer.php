<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Sources\Youtube;

use Illuminate\Support\Str;
use Inboxly\Receiver\Contracts\Parameters;
use Inboxly\Receiver\Contracts\UrlExplorer;
use Inboxly\Receiver\Sources\Rss\RssExplorer;

final class YoutubeRssExplorer implements UrlExplorer
{
    /**
     * YoutubeRssExplorer constructor.
     *
     * @param \Inboxly\Receiver\Sources\Rss\RssExplorer $rssExplorer
     */
    public function __construct(
        private RssExplorer $rssExplorer
    ){}

    /**
     * Get the unique key of the explorer.
     *
     * @return string
     */
    public function getKey(): string
    {
        return 'youtube_rss';
    }

    /**
     * Check that the explorer can be used for explore feed by specified url.
     *
     * @param string $url
     * @return bool
     */
    public function canExploreByUrl(string $url): bool
    {
        $prefixes = [
            'https://www.youtube.com/channel/',
            'https://www.youtube.com/c/',
        ];

        return !in_array($url, $prefixes) && Str::startsWith($url, $prefixes);
    }

    /**
     * Explore feed by specified url.
     *
     * @param string $url
     * @return array
     */
    public function exploreByUrl(string $url): array
    {
        return array_map(function (Parameters $parameters) {
            $parameters->setFetcherKey(YoutubeRssFetcher::KEY);
            return $parameters;
        }, $this->rssExplorer->exploreByUrl($url));
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
        return true;
    }
}
