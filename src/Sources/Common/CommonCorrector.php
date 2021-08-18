<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Sources\Common;

use Inboxly\Receiver\Contracts\EntryCorrector;
use Inboxly\Receiver\Contracts\FeedCorrector;
use Inboxly\Receiver\Entry;
use Inboxly\Receiver\Feed;
use Inboxly\TextProcessing\Handlers\Limit;
use Inboxly\TextProcessing\Handlers\RemoveHtml;
use Inboxly\TextProcessing\Handlers\SanitizeHtml;
use Inboxly\TextProcessing\Handlers\Trim;
use Inboxly\TextProcessing\Processor;

class CommonCorrector implements EntryCorrector, FeedCorrector
{
    /**
     * RssCorrector constructor.
     *
     * @param \Inboxly\TextProcessing\Processor $processor
     */
    public function __construct(
        private Processor $processor,
    ){}

    /**
     * Update Entry's properties that need correction
     *
     * @param \Inboxly\Receiver\Feed $feed
     */
    public function correctFeed(Feed $feed): void
    {
        $feed->name = $this->processor->process($feed->name, [
            RemoveHtml::class,
            Limit::with(100, ''),
            Trim::class,
        ]);

        $feed->summary = $this->processor->process($feed->summary, [
            RemoveHtml::class,
            Limit::with(200),
            Trim::class,
        ]);
    }

    /**
     * Update Entry's properties that need correction
     *
     * @param \Inboxly\Receiver\Entry $entry
     */
    public function correctEntry(Entry $entry): void
    {
        if ($entry->summary === null) {
            $entry->summary = $entry->content;
        } elseif ($entry->content === null) {
            $entry->content = $entry->summary;
        }

        $entry->name = $this->processor->process($entry->name, [
            RemoveHtml::class,
            Limit::with(100, ''),
            Trim::class,
        ]);

        $entry->summary = $this->processor->process($entry->summary, [
            RemoveHtml::class,
            Limit::with(200),
            Trim::class,
        ]);

        $entry->content = $this->processor->process($entry->content, [
            SanitizeHtml::class,
            Trim::class,
        ]);
    }
}
