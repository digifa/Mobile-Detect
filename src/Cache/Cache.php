<?php

namespace Detection\Cache;

use DateInterval;
use Psr\SimpleCache\CacheInterface;

class Cache implements CacheInterface
{
    /**
     * @var array|array{cache_key:string, cache_value:CacheItem} $cache_db
     */
    protected array $cache_db = [];

    /**
     * Counts the number of items in the cache.
     *
     * @return int The number of items in the cache.
     */
    public function count(): int
    {
        return count($this->cache_db);
    }

    /**
     * Retrieve all the keys stored in the cache.
     *
     * @return array{string} List of cache keys
     */
    public function getKeys(): array
    {
        return array_keys($this->cache_db);
    }

    /**
     * Retrieves a cache item from the cache database based on the provided key.
     *
     * @param string $key The key of the cache item to retrieve.
     * @param mixed $default The default value to return if the cache item is not found.
     * @return CacheItem|null The retrieved cache item if found, or the default value if not found.
     * @throws CacheException If an invalid cache key is provided (empty key).
     */
    public function get(string $key, mixed $default = null): CacheItem|null
    {
        if (empty($key)) {
            throw new CacheException('Invalid cache key');
        }

        return $this->cache_db[$key] ?? null;
    }

    /**
     * Saves a cache item to the cache database with the provided key, value, and time to live (ttl).
     *
     * @param string $key The key of the cache item to save.
     * @param mixed $value The value of the cache item to save.
     * @param DateInterval|int|null $ttl The time to live for the cache item. Null for infinite TTL.
     * @return bool True if the cache item was successfully saved, false otherwise.
     * @throws CacheException If an invalid cache key is provided (empty key).
     */
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        if (empty($key)) {
            throw new CacheException('Invalid cache key');
        }
        $item = new CacheItem($key, $value, $ttl);
        $this->cache_db[$key] = $item;
        return true;
    }

    /**
     * Deletes a cache item from the cache database based on the provided key.
     *
     * @param string $key The key of the cache item to delete.
     * @return bool Returns true after successfully deleting the cache item.
     */
    public function delete(string $key): bool
    {
        unset($this->cache_db[$key]);
        return true;
    }

    /**
     * Clears all cache items in the cache database.
     *
     * @return bool Returns true after successfully clearing all cache items.
     */
    public function clear(): bool
    {
        $this->cache_db = [];
        return true;
    }

    /**
     * Retrieves multiple cache items from the cache database based on the provided keys.
     *
     * @param iterable $keys The keys of the cache items to retrieve.
     * @param mixed $default The default value to return if a cache item is not found.
     * @return iterable A collection of retrieved cache items with keys matching the provided keys, or default value if not found.
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return array_map(function ($key) {
            return $this->cache_db[$key];
        }, (array)$keys);
    }

    /**
     * Sets multiple cache items in the cache database with optional time to live (TTL).
     *
     * @param iterable $values An iterable of cache item arrays containing key, value, and optional expiration time.
     * @param DateInterval|int|null $ttl The time to live (TTL) for the cache items in seconds or as a DateInterval object. Default is null.
     * @return bool Returns true if all cache items were successfully set in the cache database.
     */
    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        foreach ($values as $cacheItemArray) {
            $item = new CacheItem(...$cacheItemArray);
            $this->cache_db[$cacheItemArray['key']] = $item;
        }
        return true;
    }

    /**
     * Deletes multiple cache items from the cache database based on the provided keys.
     *
     * @param iterable $keys An iterable collection of cache item keys to delete.
     * @return bool True if all cache items were successfully deleted, false otherwise.
     */
    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            unset($this->cache_db[$key]);
        }
        return true;
    }

    /**
     * Checks if a cache item exists in the cache database based on the provided key.
     *
     * @param string $key The key of the cache item to check for existence.
     * @return bool True if the cache item exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return isset($this->cache_db[$key]);
    }
}
