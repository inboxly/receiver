<?php

declare(strict_types=1);

namespace Inboxly\Receiver;

use DateTime;

class Entry
{
    /**
     * Entry constructor.
     */
    public function __construct(
        public string $externalId,
        public ?string $name,
        public ?string $summary = null,
        public ?string $content = null,
        public ?string $url = null,
        public ?string $image = null,
        public ?string $authorName = null,
        public ?string $authorUrl = null,
        public ?DateTime $createdAt = null,
        public ?DateTime $updatedAt = null,
    )
    {
    }
}
