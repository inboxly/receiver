<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Sources\Rss;

use Embed\Embed;
use Illuminate\Support\Str;
use Inboxly\Receiver\Contracts\FeedCorrector;
use Inboxly\Receiver\Feed;

final class RssCorrector implements FeedCorrector
{
    /**
     * RssCorrector constructor.
     *
     * @param \Embed\Embed $embed
     */
    public function __construct(
        private Embed $embed,
    ){}

    /**
     * Update Entry's properties that need correction
     *
     * @param \Inboxly\Receiver\Feed $feed
     */
    public function correctFeed(Feed $feed): void
    {
        if (!$feed->summary || !$feed->image) {
            $extracted = $this->embed->get($feed->parameters->url);

            if (!$feed->summary && $extracted->description) {
                $feed->summary = Str::limit(trim($extracted->description));
            }

            $feed->image ?: $feed->image = (string)$extracted->favicon;
        }
    }
}
