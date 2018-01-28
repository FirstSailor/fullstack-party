<?php

namespace App\Model;

class Issue
{
    /**
     * @var array
     */
    private $issueData;

    /**
     * @param array $issueData
     */
    public function __construct(array $issueData)
    {
        $this->issueData = $issueData;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->issueData['number'];
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->issueData['title'];
    }

    /**
     * @return string
     */
    public function getOwner(): string
    {
        return $this->issueData['user']['login'];
    }

    /**
     * @return string
     */
    public function getRepository(): string
    {
        return $this->issueData['repository']['name'];
    }

    /**
     * @return int
     */
    public function getCommentsCount(): int
    {
        return $this->issueData['comments'];
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return new \DateTime($this->issueData['created_at']);
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->issueData['state'];
    }

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->getState() == IssueState::OPEN;
    }

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->getState() == IssueState::CLOSED;
    }

    /**
     * @return array
     */
    public function getLabels(): array
    {
        return $this->issueData['labels'];
    }
}
