<?php

namespace App\Model;

class Comment
{
    /**
     * @var array
     */
    private $commentData;

    /**
     * @param array $commentData
     */
    public function __construct(array $commentData)
    {
        $this->commentData = $commentData;
    }

    /**
     * @return string
     */
    public function getOwner(): string
    {
        return $this->commentData['user']['login'];
    }

    /**
     * @return string
     */
    public function getOwnerImageUrl(): string
    {
        return $this->commentData['user']['avatar_url'];
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return new \DateTime($this->commentData['created_at']);
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->commentData['body'];
    }
}
