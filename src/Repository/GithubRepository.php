<?php

namespace App\Repository;

use App\Client\GithubClient;
use Psr\Http\Message\ResponseInterface;

class GithubRepository
{
    /**
     * @var GithubClient
     */
    protected $client;

    /**
     * @param GithubClient $client
     */
    public function __construct(GithubClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param int $page
     * @param int $perPage
     * @param string $state
     * @return ResponseInterface
     */
    public function getPaginatedIssuesResponse(int $page, int $perPage, string $state): ResponseInterface
    {
        return $this->call('getPaginatedIssues', ['page' => $page, 'per_page' => $perPage, 'state' => $state]);
    }

    /**
     * @param string $owner
     * @param string $repo
     * @param int $number
     * @return ResponseInterface
     */
    public function getSingleIssueResponse(string $owner, string $repo, int $number): ResponseInterface
    {
        return $this->call('getSingleIssue', ['owner' => $owner, 'repo' => $repo, 'number' => $number]);
    }

    /**
     * @param string $owner
     * @param string $repo
     * @param int $number
     * @return ResponseInterface
     */
    public function getIssueCommentsResponse(string $owner, string $repo, int $number): ResponseInterface
    {
        return $this->call('getIssueComments', ['owner' => $owner, 'repo' => $repo, 'number' => $number]);
    }

    /**
     * @param string $operation
     * @param array $arguments
     * @return ResponseInterface
     */
    protected function call(string $operation, array $arguments = []): ResponseInterface
    {
        return $this->client->execute(
            $this->client->getCommand($operation, $arguments)
        );
    }
}
