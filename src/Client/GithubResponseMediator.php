<?php

namespace App\Client;

use Psr\Http\Message\ResponseInterface;

class GithubResponseMediator
{
    /**
     * @param ResponseInterface $response
     * @return array|string
     */
    public static function getContent(ResponseInterface $response)
    {
        $body = (string) $response->getBody();
        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') === 0) {
            return \GuzzleHttp\json_decode($body, true);
        }

        return $body;
    }

    /**
     * @param ResponseInterface $response
     * @return array|null
     */
    public static function getPaginationData(ResponseInterface $response): ?array
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
     * @param string $name
     * @return string|null
     */
    public static function getHeader(ResponseInterface $response, string $name): ?string
    {
        $headers = $response->getHeader($name);

        return array_shift($headers);
    }
}
