<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Contracts;

use Inboxly\Receiver\Feed;

interface FeedCorrector
{
    /**
     * Update Entry's properties that need correction
     *
     * @param \Inboxly\Receiver\Feed $feed
     */
    public function correctFeed(Feed $feed): void;
}
