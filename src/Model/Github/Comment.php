<?php

namespace App\Model\Github;

use JMS\Serializer\Annotation as JMS;

class Comment
{
    /**
     * @var User
     *
     * @JMS\Type("App\Model\Github\User")
     * @JMS\SerializedName("user")
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @JMS\Type("DateTime")
     * @JMS\SerializedName("created_at")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("body")
     */
    private $body;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}
