<?php

namespace App\Factory;

use GuzzleHttp\Command\Guzzle\Description;

class ClientDescriptionFactory
{
    /**
     * @param array $configuration
     * @return Description
     */
    public function create(array $configuration): Description
    {
        return new Description($configuration);
    }
}
