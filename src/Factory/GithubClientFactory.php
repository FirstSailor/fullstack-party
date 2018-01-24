<?php

namespace App\Factory;

use App\Client\GithubClient;
use App\Manager\GithubUserManager;
use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\HandlerStack;

class GithubClientFactory
{
    /**
     * @param Description $description
     * @param GithubUserManager $userManager
     * @return GithubClient
     */
    public function create(Description $description, GithubUserManager $userManager): GithubClient
    {
        return new GithubClient(
            new Client(['handler' => $this->getMiddlewaresStack($userManager->getToken())]),
            $description,
            null,
            null,
            null,
            ['process' => false]
        );
    }

    /**
     * @param string $authToken
     * @return HandlerStack
     */
    protected function getMiddlewaresStack(string $authToken): HandlerStack
    {
        $handlerStack = HandlerStack::create();
        $handlerStack->push(Middleware::mapRequest(function (RequestInterface $request) use ($authToken) {
            return $request->withHeader('Authorization', sprintf('token %s', $authToken));
        }));

        return $handlerStack;
    }
}
