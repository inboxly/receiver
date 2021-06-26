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
        $feed->title = $this->processor->process($feed->title, [
            RemoveHtml::class,
            Limit::with(100, ''),
            Trim::class,
        ]);

        $feed->description = $this->processor->process($feed->description, [
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
        if ($entry->description === null) {
            $entry->description = $entry->text;
        } elseif ($entry->text === null) {
            $entry->text = $entry->description;
        }

        $entry->title = $this->processor->process($entry->title, [
            RemoveHtml::class,
            Limit::with(100, ''),
            Trim::class,
        ]);

        $entry->description = $this->processor->process($entry->description, [
            RemoveHtml::class,
            Limit::with(200),
            Trim::class,
        ]);

        $entry->text = $this->processor->process($entry->text, [
            SanitizeHtml::class,
            Trim::class,
        ]);
    }
}
