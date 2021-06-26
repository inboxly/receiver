<?php

declare(strict_types=1);

namespace Inboxly\Receiver\Contracts;

use ArrayObject;

abstract class Parameters extends ArrayObject
{
    /**
     * Fetcher key to be used to get feed for these parameters
     *
     * @var string
     */
    private string $fetcherKey;

    /**
     * Parameters constructor.
     *
     * @param string $fetcherKey
     * @param array $parameters
     */
    public function __construct(string $fetcherKey, array $parameters = [])
    {
        $this->setFetcherKey($fetcherKey);

        parent::__construct($parameters, ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Get unique feed id based on these feed parameters
     *
     * @return string
     */
    abstract public function getFeedId(): string;

    /**
     * Get fetcher key to be used to get feed for these parameters.
     *
     * @return string
     */
    public function getFetcherKey(): string
    {
        return $this->fetcherKey;
    }

    /**
     * Set another fetcher key to be used to get feed for these parameters.
     *
     * @param string $fetcherKey
     */
    public function setFetcherKey(string $fetcherKey): void
    {
        $this->fetcherKey = $fetcherKey;
    }
}
