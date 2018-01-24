<?php

namespace App\Manager;

use App\Client\GithubResponseMediator;
use App\Repository\GithubRepository;
use Pagerfanta\Adapter\FixedAdapter;
use Pagerfanta\Pagerfanta;
use Psr\Http\Message\ResponseInterface;

class GithubPaginatedDataManager
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
     * @var int
     */
    private $issuesPerPage;

    /**
     * @param GithubRepository $repository
     * @param GithubResponseMediator $responseMediator
     * @param int $issuesPerPage
     */
    public function __construct(
        GithubRepository $repository,
        GithubResponseMediator $responseMediator,
        int $issuesPerPage
    ) {
        $this->repository = $repository;
        $this->responseMediator = $responseMediator;
        $this->issuesPerPage = $issuesPerPage;
    }

    /**
     * @param int $page
     * @return Pagerfanta
     */
    public function getIssuesPager(int $page): Pagerfanta
    {
        $response = $this->repository->getPaginatedIssues($page, $this->issuesPerPage);
        $content = $this->responseMediator->getContent($response);

        $pagerAdapter = new FixedAdapter($this->getTotalResults($response), $content);
        $pager = new Pagerfanta($pagerAdapter);
        $pager->setMaxPerPage($this->issuesPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }

    /**
     * @param ResponseInterface $response
     * @return int
     */
    protected function getTotalResults(ResponseInterface $response): int
    {
        $totalPages = $this->responseMediator->getTotalResultsCount($response);

        return null !== $totalPages ? $totalPages * $this->issuesPerPage : $this->issuesPerPage;
    }
}
