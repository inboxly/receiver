<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Managers;

use Inboxly\Receiver\Contracts\QueryExplorer;
use Inboxly\Receiver\Contracts\UrlExplorer;

final class ExplorerManager
{
    /**
     * @var \Inboxly\Receiver\Contracts\QueryExplorer[]
     */
    private array $queryExplorers = [];

    /**
     * @var \Inboxly\Receiver\Contracts\UrlExplorer[]
     */
    private array $urlExplorers = [];

    /**
     * Add an instance of explorer to the manager
     *
     * @param \Inboxly\Receiver\Contracts\QueryExplorer|\Inboxly\Receiver\Contracts\UrlExplorer $explorer
     */
    public function addExplorer(QueryExplorer|UrlExplorer $explorer)
    {
        if (is_subclass_of($explorer, UrlExplorer::class)) {
            $this->urlExplorers[$explorer->getKey()] = $explorer;
        }

        if (is_subclass_of($explorer, QueryExplorer::class)) {
            $this->queryExplorers[$explorer->getKey()] = $explorer;
        }
    }

    /**
     * Explore feeds by specified query or url.
     *
     * Use second argument for specify particular explorer
     *
     * @param string $query
     * @param string|null $explorerKey
     * @return array
     */
    public function explore(string $query, ?string $explorerKey = null): array
    {
        if ($this->isUrl($query)) {
            return $explorerKey ? $this->exploreByUrlAndKey($query, $explorerKey) : $this->exploreByUrl($query);
        }

        return $explorerKey ? $this->exploreByQueryAndKey($query, $explorerKey) : $this->exploreByQuery($query);
    }

    /**
     * Check that string is valid url
     *
     * @param string $url
     * @return bool
     */
    private function isUrl(string $url): bool
    {
        $sanitizedUrl = filter_var($url, FILTER_SANITIZE_URL);
        $validatedUrl = filter_var($sanitizedUrl ?: '', FILTER_VALIDATE_URL);

        return (bool)$validatedUrl;
    }

    /**
     * Explore feeds by specified query.
     *
     * @param string $query
     * @return array
     */
    private function exploreByQuery(string $query): array
    {
        $notExclusiveExplorers = [];

        foreach ($this->queryExplorers as $explorer) {
            if ($explorer->canExploreByQuery($query) === false) {
                continue;
            }

            if ($explorer->isExclusive()) {
                return $explorer->exploreByQuery($query);
            }

            $notExclusiveExplorers[] = $explorer;
        }

        return array_reduce(
            $notExclusiveExplorers,
            fn(array $feeds, QueryExplorer $explorer) => array_merge($feeds, $explorer->exploreByQuery($query)),
            []
        );
    }

    /**
     * Explore feeds by specified query using particular explorer.
     *
     * @param string $query
     * @param string $explorerKey
     * @return array
     */
    private function exploreByQueryAndKey(string $query, string $explorerKey): array
    {
        if (array_key_exists($explorerKey, $this->queryExplorers)) {
            $explorer = $this->queryExplorers[$explorerKey];
            return $explorer->canExploreByQuery($query) ? $explorer->exploreByQuery($query) : [];
        }

        return [];
    }

    /**
     * Explore feeds by specified url.
     *
     * @param string $url
     * @return array
     */
    private function exploreByUrl(string $url): array
    {
        $notExclusiveExplorers = [];

        foreach ($this->urlExplorers as $explorer) {
            if ($explorer->canExploreByUrl($url) === false) {
                continue;
            }

            if ($explorer->isExclusive()) {
                return $explorer->exploreByUrl($url);
            }

            $notExclusiveExplorers[] = $explorer;
        }

        return array_reduce(
            $notExclusiveExplorers,
            fn(array $feeds, UrlExplorer $explorer) => array_merge($feeds, $explorer->exploreByUrl($url)),
            []
        );
    }

    /**
     * Explore feeds by specified url using particular explorer.
     *
     * @param string $url
     * @param string $explorerKey
     * @return array
     */
    private function exploreByUrlAndKey(string $url, string $explorerKey): array
    {
        if (array_key_exists($explorerKey, $this->urlExplorers)) {
            $explorer = $this->urlExplorers[$explorerKey];
            return $explorer->canExploreByUrl($url) ? $explorer->exploreByUrl($url) : [];
        }

        return [];
    }
}
