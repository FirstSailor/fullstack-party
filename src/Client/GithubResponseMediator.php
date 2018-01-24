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
            $content = json_decode($body, true);
            if (JSON_ERROR_NONE === json_last_error()) {
                return $content;
            }
        }

        return $body;
    }

    /**
     * @param ResponseInterface $response
     * @return array|null
     */
    public static function getPagination(ResponseInterface $response): ?array
    {
        if (!$response->hasHeader('Link')) {
            return null;
        }

        $header = self::getHeader($response, 'Link');
        $pagination = array();
        foreach (explode(',', $header) as $link) {
            preg_match('/<(.*)>; rel="(.*)"/i', trim($link, ','), $match);

            if (3 === count($match)) {
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
