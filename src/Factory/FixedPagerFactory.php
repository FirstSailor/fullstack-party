<?php

namespace App\Factory;

use Pagerfanta\Adapter\FixedAdapter;
use Pagerfanta\Pagerfanta;

class FixedPagerFactory
{
    /**
     * @var PagerFactory
     */
    protected $pagerFactory;

    /**
     * @param PagerFactory $pagerFactory
     */
    public function __construct(PagerFactory $pagerFactory)
    {
        $this->pagerFactory = $pagerFactory;
    }

    /**
     * @param array $results
     * @param int $totalResults
     * @return Pagerfanta
     */
    public function create(array $results, int $totalResults): Pagerfanta
    {
        $pagerAdapter = new FixedAdapter($totalResults, $results);

        return $this->pagerFactory->create($pagerAdapter);
    }
}
