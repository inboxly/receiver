<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Tests\Sources\Rss;

use Inboxly\Receiver\Sources\Rss\RssParameters;
use PHPUnit\Framework\TestCase;

/**
 * @see \Inboxly\Receiver\Sources\Rss\RssParameters
 */
class RssParametersTest extends TestCase
{
    /**
     * @test
     * @see \Inboxly\Receiver\Sources\Rss\RssParameters::getFeedId()
     */
    public function is_return_url_as_feed_id()
    {
        // Setup
        $url = 'https://example.com';
        $parameters = new RssParameters($url);

        // Asserts
        $this->assertSame($url, $parameters->getFeedId());
    }

    /**
     * @test
     * @see \Inboxly\Receiver\Sources\Rss\RssParameters::getFetcherKey()
     */
    public function is_return_fetcher_key()
    {
        // Setup
        $url = 'https://example.com';
        $parameters = new RssParameters($url);

        // Asserts
        $this->assertSame('rss', $parameters->getFetcherKey());
    }

    /**
     * @test
     * @see \Inboxly\Receiver\Sources\Rss\RssParameters::setFetcherKey()
     */
    public function is_can_set_fetcher_key()
    {
        // Setup
        $url = 'https://example.com';
        $parameters = new RssParameters($url);

        // Run
        $parameters->setFetcherKey('other-key');

        // Asserts
        $this->assertSame('other-key', $parameters->getFetcherKey());
    }
}
