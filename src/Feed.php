<?php

declare(strict_types=1);

namespace Inboxly\Receiver;

use DateTime;
use Inboxly\Receiver\Contracts\Parameters;
use InvalidArgumentException;

class Feed
{
    /**
     * Feed constructor.
     */
    public function __construct(
        public Parameters $parameters,
        public ?string $name = null,
        public ?string $summary = null,
        public ?string $url = null,
        public ?string $image = null,
        public ?string $authorName = null,
        public ?string $authorUrl = null,
        public ?string $language = null,
        public ?DateTime $updatedAt = null,
        public ?DateTime $nextUpdateAt = null,
        public array $entries = [],
    )
    {
        foreach ($this->entries as $entry) {
            if (!$entry instanceof Entry) {
                throw new InvalidArgumentException(
                    '"Entries" argument must be instance of ' . Entry::class
                );
            }
        }
    }
}
