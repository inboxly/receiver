<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Tests\Sources\Rss;

use FeedIo\Adapter\Guzzle\Client as ClientAdapter;
use FeedIo\FeedIo;
use GuzzleHttp\Client;
use Illuminate\Container\Container;
use Inboxly\Receiver\Sources\Rss\RssCorrector;
use Inboxly\Receiver\Sources\Rss\RssFetcher;
use Inboxly\Receiver\Sources\Rss\RssParameters;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * @see \Inboxly\Receiver\Sources\Rss\RssFetcher
 */
class RssFetcherTest extends TestCase
{
    /**
     * @test
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @see \Inboxly\Receiver\Sources\Rss\RssFetcher::getKey()
     */
    public function is_return_key()
    {
        $fetcher = $this->makeRssFetcher();

        $this->assertSame('rss', $fetcher->getKey());
    }

    /**
     * @test
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @see \Inboxly\Receiver\Sources\Rss\RssFetcher::getCorrectors()
     */
    public function is_return_correctors()
    {
        // Setup
        $fetcher = $this->makeRssFetcher();

        // Asserts
        $this->assertIsArray($fetcher->getCorrectors());
        $this->assertCount(1, $fetcher->getCorrectors());
        $this->assertInstanceOf(RssCorrector::class, $fetcher->getCorrectors()[0]);
    }

    /**
     * @test
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @see \Inboxly\Receiver\Sources\Rss\RssFetcher::getCorrectors()
     */
    public function is_return_parameters()
    {
        // Setup
        $fetcher = $this->makeRssFetcher();
        $url = 'https://example.site';
        $parameters = $fetcher->makeParameters(['url' => $url]);

        // Asserts
        $this->assertInstanceOf(RssParameters::class, $parameters);
        $this->assertSame($url, $parameters->url);
    }

    /**
     * @test
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @see \Inboxly\Receiver\Sources\Rss\RssFetcher::getCorrectors()
     */
    public function is_can_fetch_feed()
    {
        // Setup
        $this->makeRssFetcher();

        // Asserts
        $this->markTestIncomplete('Todo: make mock of feedIo');
    }

    /**
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function makeRssFetcher(): RssFetcher
    {
        $container = new Container();

        $container->instance(FeedIo::class,
            new FeedIo(new ClientAdapter(new Client()), new NullLogger())
        );

        return $container->make(RssFetcher::class);
    }
}
