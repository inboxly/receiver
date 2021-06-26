<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Contracts;

use Inboxly\Receiver\Entry;

interface EntryCorrector
{
    /**
     * Update Entry's properties that need correction
     *
     * @param \Inboxly\Receiver\Entry $entry
     */
    public function correctEntry(Entry $entry): void;
}
