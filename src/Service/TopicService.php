<?php

namespace DMarti\ExamplesSymfony5\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class TopicService
{
    public function __construct(
        private CacheInterface $cache,
        private HttpClientInterface $topicHttpClient
    ) {
    }

    public function getTopics(): array
    {
        return $this->cache->get('topics-default', function (CacheItemInterface $cacheItem): array {
            $cacheItem->expiresAfter(5); // if unset, the cached item never expires until the cache is cleared manually
            $response = $this->topicHttpClient->request(
                Request::METHOD_GET,
                '/d-marti/examples-symfony5/main/public/topics.json'
            );

            return $response->toArray();
        });
    }
}
