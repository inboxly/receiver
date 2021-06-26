<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Sources\Reddit;

use Illuminate\Support\Str;
use Inboxly\Receiver\Contracts\UrlExplorer;
use Inboxly\Receiver\Sources\Rss\RssParameters;

final class RedditRssExplorer implements UrlExplorer
{
    /**
     * Get the unique key of the explorer.
     *
     * @return string
     */
    public function getKey(): string
    {
        return 'reddit_rss';
    }

    /**
     * Check that the explorer can be used for explore feed by specified url.
     *
     * @param string $url
     * @return bool
     */
    public function canExploreByUrl(string $url): bool
    {
        $isMainUrl = in_array($url, [
            'https://www.reddit.com',
            'https://www.reddit.com/',
            'https://www.reddit.com/.rss',
        ]);

        $prefixes = [
            'https://www.reddit.com/r/',
            'https://www.reddit.com/u/',
            'https://www.reddit.com/user/',
            'https://www.reddit.com/dompain/',
        ];

        $isPrefixedUrl = !in_array($url, $prefixes) && Str::startsWith($url, $prefixes);

        return $isMainUrl || $isPrefixedUrl;
    }

    /**
     * Explore feed by specified url.
     *
     * @param string $url
     * @return array
     */
    public function exploreByUrl(string $url): array
    {
        $parameters = new RssParameters(Str::finish($url, '.rss'));
        $parameters->setFetcherKey(RedditRssFetcher::KEY);

        return [$parameters];
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
