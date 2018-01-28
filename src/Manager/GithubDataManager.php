<?php

namespace App\Manager;

use App\Client\GithubResponseMediator;
use App\Model\Github\Comment;
use App\Model\Github\Issue;
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

        return $this->parseResponse($response, Issue::class);
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

        return $this->parseResponse($response, sprintf('array<%s>', Comment::class));
    }

    /**
     * @param ResponseInterface $response
     * @param string|null $deserializationType
     * @return mixed
     */
    protected function parseResponse(ResponseInterface $response, string $deserializationType = null)
    {
        return $this->responseMediator->getContent($response, $deserializationType);
    }
}
