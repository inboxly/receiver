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
        public ?string $title,
        public ?string $description = null,
        public ?string $text = null,
        public ?string $link = null,
        public ?string $image = null,
        public ?string $authorName = null,
        public ?string $authorLink = null,
        public ?DateTime $createdAt = null,
        public ?DateTime $updatedAt = null,
    )
    {
    }
}
