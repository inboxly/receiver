<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Sources\Rss;

use Inboxly\Receiver\Contracts\Parameters;

class RssParameters extends Parameters
{
    /**
     * RssParameters constructor.
     *
     * @param string $url
     */
    public function __construct(string $url)
    {
        parent::__construct('rss', ['url' => $url]);
    }

    /**
     * Get unique feed id based on these feed parameters
     *
     * @return string
     */
    public function getFeedId(): string
    {
        return $this->offsetGet('url');
    }
}
