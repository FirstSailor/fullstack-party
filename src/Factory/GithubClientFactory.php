<?php

namespace App\Factory;

use App\Client\GithubClient;
use App\Manager\GithubUserManager;
use Doctrine\Common\Cache\FilesystemCache;
use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Middleware;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\DoctrineCacheStorage;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\HandlerStack;

class GithubClientFactory
{
    /**
     * @param Description $description
     * @param GithubUserManager $userManager
     * @param string $cacheDir
     * @return GithubClient
     */
    public function create(Description $description, GithubUserManager $userManager, string $cacheDir): GithubClient
    {
        $handlerStack = HandlerStack::create();
        $handlerStack->push($this->getMapRequestMiddleware($userManager->getToken()), 'map_request');
        $handlerStack->push($this->getCacheMiddleware($cacheDir), 'cache');

        return new GithubClient(
            new Client(['handler' => $handlerStack]),
            $description,
            null,
            null,
            null,
            ['process' => false]
        );
    }

    /**
     * @param string $authToken
     * @return callable
     */
    protected function getMapRequestMiddleware(string $authToken): callable
    {
        return Middleware::mapRequest(function (RequestInterface $request) use ($authToken) {
            return $request->withHeader('Authorization', sprintf('token %s', $authToken));
        });
    }

    /**
     * @param string $cacheDir
     * @return callable
     */
    protected function getCacheMiddleware(string $cacheDir): callable
    {
        return new CacheMiddleware(
            new PrivateCacheStrategy(
                new DoctrineCacheStorage(
                    new FilesystemCache($cacheDir . '/github_client')
                )
            )
        );
    }
}
