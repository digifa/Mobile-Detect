<?php

declare(strict_types=1);

namespace Detection\Cache;

use DateInterval;

/**
 * Simple cache item (key, value, ttl) that is being
 * used by all the detection methods of Mobile Detect class.
 */
class CacheItem
{
    /**
     * @var string Unique key for the cache record.
     */
    protected string $key;

    /**
     * @var mixed Mobile Detect only needs to store booleans (e.g. "isMobile" => true)
     */
    protected mixed $value = null;

    /**
     * @var DateInterval|int|null Time to live in seconds. Set to 0 for no cache expiration
     */
    protected DateInterval|int|null $ttl = 0;

    public function __construct(string $key, mixed $value = null, DateInterval|int|null $ttl = null)
    {
        $this->key = $key;
        if (!is_null($value)) {
            $this->value = $value;
        }
        $this->ttl = $ttl;
    }

    /**
     * Get the key of the entity
     *
     * @return string The key of the entity
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get the value.
     *
     * @return mixed The value or null if no value is set.
     */
    public function get(): mixed
    {
        return $this->value;
    }

    /**
     * Set the value.
     *
     * @param mixed $value The value to set.
     *
     * @return void
     */
    public function set(mixed $value): void
    {
        $this->value = $value;
    }

    /**
     * Get the TTL (Time to Live) value.
     *
     * @return DateInterval|int|null The TTL value in seconds or null if no TTL is set.
     */
    public function getTtl(): DateInterval|int|null
    {
        return $this->ttl;
    }
}
