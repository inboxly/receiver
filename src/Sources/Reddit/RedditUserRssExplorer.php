<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Sources\Reddit;

use Inboxly\Receiver\Contracts\QueryExplorer;
use Inboxly\Receiver\Sources\Rss\RssParameters;

final class RedditUserRssExplorer implements QueryExplorer
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
     * Check that the explorer can be used for explore feed by specified query.
     *
     * @param string $query
     * @return bool
     */
    public function canExploreByQuery(string $query): bool
    {
        $usernamePattern = '/^[a-z_\-0-9]{3,20}$/i';

        return (bool)preg_match($usernamePattern, $query);
    }

    /**
     * Explore feed by specified query.
     *
     * @param string $query
     * @return array
     */
    public function exploreByQuery(string $query): array
    {
        $url = "https://www.reddit.com/user/$query.rss";
        $parameters = new RssParameters($url);
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
        return false;
    }
}
