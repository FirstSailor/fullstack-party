<?php

namespace App\Manager;

use App\Model\GithubUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GithubUserManager
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->getUser()->getAccessToken()->getToken();
    }

    /**
     * @return GithubUser
     */
    public function getUser(): GithubUser
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}
