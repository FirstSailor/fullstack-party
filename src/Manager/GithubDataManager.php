<?php

namespace App\Manager;

use App\Client\GithubResponseMediator;
use App\Model\Comment;
use App\Model\Issue;
use App\Repository\GithubRepository;
use Psr\Http\Message\ResponseInterface;

class GithubDataManager
{
    /**
     * @var GithubRepository
     */
    protected $repository;

    /**
     * @var GithubResponseMediator
     */
    protected $responseMediator;

    /**
     * @param GithubRepository $repository
     * @param GithubResponseMediator $responseMediator
     */
    public function __construct(GithubRepository $repository, GithubResponseMediator $responseMediator)
    {
        $this->repository = $repository;
        $this->responseMediator = $responseMediator;
    }

    /**
     * @param string $owner
     * @param string $repo
     * @param int $number
     * @return Issue
     */
    public function getSingleIssue(string $owner, string $repo, int $number): Issue
    {
        $response = $this->repository->getSingleIssueResponse($owner, $repo, $number);

        return new Issue($this->parseResponse($response));
    }

    /**
     * @param string $owner
     * @param string $repo
     * @param int $number
     * @return array
     */
    public function getIssueComments(string $owner, string $repo, int $number): array
    {
        $response = $this->repository->getIssueCommentsResponse($owner, $repo, $number);

        return array_map(
            function (array $item) {
                return new Comment($item);
            },
            $this->parseResponse($response)
        );
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    protected function parseResponse(ResponseInterface $response): array
    {
        return $this->responseMediator->getContent($response);
    }
}
