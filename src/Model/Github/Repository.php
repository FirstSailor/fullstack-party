<?php

namespace App\Model\Github;

use JMS\Serializer\Annotation as JMS;

class Repository
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     */
    private $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
