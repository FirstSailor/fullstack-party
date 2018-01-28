<?php

namespace App\Manager;

use App\Client\GithubResponseMediator;
use App\Factory\FixedPagerFactory;
use App\Model\Issue;
use App\Model\IssueState;
use App\Repository\GithubRepository;
use Pagerfanta\Pagerfanta;
use Psr\Http\Message\ResponseInterface;

class GithubPaginatedDataManager extends GithubDataManager
{
    /**
     * @var FixedPagerFactory
     */
    protected $pagerFactory;

    /**
     * @var int
     */
    private $issuesPerPage;

    /**
     * @param GithubRepository $repository
     * @param GithubResponseMediator $responseMediator
     * @param FixedPagerFactory $pagerFactory
     * @param int $issuesPerPage
     */
    public function __construct(
        GithubRepository $repository,
        GithubResponseMediator $responseMediator,
        FixedPagerFactory $pagerFactory,
        int $issuesPerPage
    ) {
        parent::__construct($repository, $responseMediator);

        $this->pagerFactory = $pagerFactory;
        $this->issuesPerPage = $issuesPerPage;
    }

    /**
     * @param int $page
     * @return Pagerfanta
     */
    public function getIssuesPager(int $page): Pagerfanta
    {
        $response = $this->repository->getPaginatedIssuesResponse($page, $this->issuesPerPage, IssueState::ALL);
        $content = array_map(
            function (array $item) {
                return new Issue($item);
            },
            $this->parseResponse($response)
        );

        $pager = $this->pagerFactory->create($content, $this->getTotalResultsCount($response));
        $pager->setMaxPerPage($this->issuesPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }

    /**
     * @param string $state
     * @return int
     */
    public function getIssuesCountByState(string $state): int
    {
        $response = $this->repository->getPaginatedIssuesResponse(1, $this->issuesPerPage, $state);
        $totalPages = $this->responseMediator->getTotalPagesCount($response);

        if ($totalPages > 1) {
            $lastPageResponse = $this->repository
                ->getPaginatedIssuesResponse($totalPages, $this->issuesPerPage, $state);

            return count($this->parseResponse($lastPageResponse)) + $this->issuesPerPage * ($totalPages - 1);
        }

        return count($this->parseResponse($response));
    }

    /**
     * @param ResponseInterface $response
     * @return int
     */
    protected function getTotalResultsCount(ResponseInterface $response): int
    {
        $totalPages = $this->responseMediator->getTotalPagesCount($response);

        return null !== $totalPages ? $totalPages * $this->issuesPerPage : $this->issuesPerPage;
    }
}
