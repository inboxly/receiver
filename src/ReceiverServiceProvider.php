<?php

declare(strict_types=1);

namespace Inboxly\Receiver;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Inboxly\Receiver\Managers\ExplorerManager;
use Inboxly\Receiver\Managers\FetcherManager;
use Inboxly\Receiver\Sources\Common\CommonCorrector;
use Inboxly\Receiver\Sources\Reddit\RedditRssExplorer;
use Inboxly\Receiver\Sources\Reddit\RedditRssFetcher;
use Inboxly\Receiver\Sources\Reddit\RedditUserRssExplorer;
use Inboxly\Receiver\Sources\Rss\RssCorrector;
use Inboxly\Receiver\Sources\Rss\RssExplorer;
use Inboxly\Receiver\Sources\Rss\RssFetcher;
use Inboxly\Receiver\Sources\Youtube\YoutubeRssExplorer;
use Inboxly\Receiver\Sources\Youtube\YoutubeRssFetcher;

class ReceiverServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Explorers to register a shared bindings
     *
     * @var \Inboxly\Receiver\Contracts\UrlExplorer[]|\Inboxly\Receiver\Contracts\QueryExplorer[]
     */
    protected array $explorers = [
        RssExplorer::class,
        YoutubeRssExplorer::class,
        RedditRssExplorer::class,
        RedditUserRssExplorer::class,
    ];

    /**
     * Fetchers to register a shared bindings
     *
     * @var \Inboxly\Receiver\Contracts\Fetcher[]
     */
    protected array $fetchers = [
        RssFetcher::class,
        RedditRssFetcher::class,
        YoutubeRssFetcher::class
    ];

    /**
     * Correctors to register a shared bindings
     *
     * @var \Inboxly\Receiver\Contracts\FeedCorrector[]|\Inboxly\Receiver\Contracts\EntryCorrector[]
     */
    protected array $correctors = [
        CommonCorrector::class,
        RssCorrector::class,
    ];

    /**
     * Register a bindings of inbox services in the container.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->bindExplorers();
        $this->bindFetchers();
        $this->bindCorrectors();
        $this->bindExplorerManager();
        $this->bindFetcherManager();
    }

    /**
     * Register a shared bindings of all explorers in the container.
     *
     * @return void
     */
    protected function bindExplorers(): void
    {
        foreach ($this->explorers as $explorer) {
            $this->app->singleton($explorer);
        }
    }

    /**
     * Register a shared bindings of all fetchers in the container.
     *
     * @return void
     */
    protected function bindFetchers(): void
    {
        foreach ($this->fetchers as $fetcher) {
            $this->app->singleton($fetcher);
        }
    }

    /**
     * Register a shared bindings of all correctors in the container.
     *
     * @return void
     */
    protected function bindCorrectors(): void
    {
        foreach ($this->correctors as $corrector) {
            $this->app->singleton($corrector);
        }
    }

    /**
     * Register a binding of explorer manager in the container.
     *
     * @return void
     */
    protected function bindExplorerManager(): void
    {
        $this->app->singleton(ExplorerManager::class, function (): ExplorerManager {
            $manager = new ExplorerManager();

            foreach ($this->explorers as $explorer) {
                $manager->addExplorer($this->app->make($explorer));
            }

            return $manager;
        });
    }

    /**
     * Register a bindings of fetcher manager in the container.
     *
     * @return void
     */
    protected function bindFetcherManager(): void
    {
        $this->app->singleton(FetcherManager::class, function (): FetcherManager {
            $manager = new FetcherManager(
                $this->app->make(CommonCorrector::class)
            );

            foreach ($this->fetchers as $fetcher) {
                $manager->addFetcher($this->app->make($fetcher));
            }

            return $manager;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            CommonCorrector::class,
            ExplorerManager::class,
            FetcherManager::class,
        ];
    }
}
