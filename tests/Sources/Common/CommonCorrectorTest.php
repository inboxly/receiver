<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Tests\Sources\Common;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Inboxly\Receiver\Entry;
use Inboxly\Receiver\Sources\Common\CommonCorrector;
use PHPUnit\Framework\TestCase;

/**
 * @see \Inboxly\Receiver\Sources\Common\CommonCorrector
 */
class CommonCorrectorTest extends TestCase
{

    /**
     * @test
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @see \Inboxly\Receiver\Sources\Common\CommonCorrector::correctEntry()
     */
    public function is_remove_html_from_entry_title()
    {
        // Setup
        $corrector = $this->makeCorrector();
        $entry = $this->getEntry();
        $entry->name = 'Title with <a href="https://example.com">link</a>.';

        // Run
        $corrector->correctEntry($entry);

        // Asserts
        $this->assertSame('Title with link.', $entry->name);
    }

    /**
     * @test
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @see \Inboxly\Receiver\Sources\Common\CommonCorrector::correctEntry()
     */
    public function is_remove_html_from_entry_description()
    {
        // Setup
        $corrector = $this->makeCorrector();
        $entry = $this->getEntry();
        $entry->summary = 'Description with <a href="https://example.com">link</a>.';

        // Run
        $corrector->correctEntry($entry);

        // Asserts
        $this->assertSame('Description with link.', $entry->summary);
    }


    /**
     * @test
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @see \Inboxly\Receiver\Sources\Common\CommonCorrector::correctEntry()
     */
    public function is_sanitize_html_in_entry_text()
    {
        // Setup
        $corrector = $this->makeCorrector();
        $entry = $this->getEntry();
        $entry->content = 'Text with <a href="https://example.com">link</a>.';

        // Run
        $corrector->correctEntry($entry);

        // Asserts
        $this->assertSame(
            '<p>Text with <a href="https://example.com" target="_blank" rel="noreferrer noopener">link</a>.</p>',
            $entry->content
        );
    }

    // todo: Add more tests

    /**
     * @return \Inboxly\Receiver\Entry
     */
    private function getEntry(): Entry
    {
        return new Entry('id', 'title');
    }

    /**
     * @return \Inboxly\Receiver\Sources\Common\CommonCorrector
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function makeCorrector(): CommonCorrector
    {
        $container = new Container();
        $container->instance(ContainerContract::class, $container);

        return $container->make(CommonCorrector::class);
    }
}
