<?php

namespace App\Factory;

use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Pagerfanta;

class PagerFactory
{
    /**
     * @param AdapterInterface $adapter
     * @return Pagerfanta
     */
    public function create(AdapterInterface $adapter): Pagerfanta
    {
        return new Pagerfanta($adapter);
    }
}
