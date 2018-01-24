<?php

namespace App\Client;

use Psr\Http\Message\ResponseInterface;

class GithubResponseMediator
{
    /**
     * @param ResponseInterface $response
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getContent(ResponseInterface $response): array
    {
        $body = (string) $response->getBody();
        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') === 0) {
            return \GuzzleHttp\json_decode($body, true);
        }

        throw new \InvalidArgumentException('Response content must be type of json');
    }

    /**
     * @param ResponseInterface $response
     * @return array|null
     */
    public function getPaginationData(ResponseInterface $response): ?array
    {
        if (!$response->hasHeader('Link')) {
            return null;
        }

        $header = self::getHeader($response, 'Link');
        $pagination = array();
        foreach (explode(',', $header) as $link) {
            preg_match('/<(.*)>; rel="(.*)"/i', trim($link, ','), $match);

            if (count($match) === 3) {
                $pagination[$match[2]] = $match[1];
            }
        }

        return $pagination;
    }

    /**
     * @param ResponseInterface $response
     * @return int|null
     */
    public function getTotalResultsCount(ResponseInterface $response): ?int
    {
        $paginationData = $this->getPaginationData($response);

        if (isset($paginationData['last']) && preg_match('/\?page=(\d+)/', $paginationData['last'], $pagesCount)) {
            return (int) array_pop($pagesCount);
        }

        if (isset($paginationData['prev']) && preg_match('/\?page=(\d+)/', $paginationData['prev'], $pagesCount)) {
            return (int) array_pop($pagesCount) + 1;
        }

        return null;
    }

    /**
     * @param ResponseInterface $response
     * @param string $name
     * @return string|null
     */
    public function getHeader(ResponseInterface $response, string $name): ?string
    {
        $headers = $response->getHeader($name);

        return array_shift($headers);
    }
}
