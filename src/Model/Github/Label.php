<?php

namespace App\Model\Github;

use JMS\Serializer\Annotation as JMS;

class Label
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     */
    private $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("color")
     */
    private $color;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }
}
