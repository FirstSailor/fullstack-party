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
     * @return ResponseInterface
     */
    public function getPaginatedIssues(int $page, int $perPage): ResponseInterface
    {
        return $this->call('getPaginatedIssues', ['page' => $page, 'per_page' => $perPage]);
    }

    /**
     * @param string $owner
     * @param string $repo
     * @param int $number
     * @return ResponseInterface
     */
    public function getSingleIssue(string $owner, string $repo, int $number): ResponseInterface
    {
        return $this->call('getSingleIssue', ['owner' => $owner, 'repo' => $repo, 'number' => $number]);
    }

    /**
     * @param string $owner
     * @param string $repo
     * @param int $number
     * @return ResponseInterface
     */
    public function getIssueComments(string $owner, string $repo, int $number): ResponseInterface
    {
        return $this->call('getIssueComments', ['owner' => $owner, 'repo' => $repo, 'number' => $number]);
    }

    /**
     * @param string $operation
     * @param array $arguments
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    protected function call(string $operation, array $arguments = [])
    {
        return $this->client->execute(
            $this->client->getCommand($operation, $arguments)
        );
    }
}
