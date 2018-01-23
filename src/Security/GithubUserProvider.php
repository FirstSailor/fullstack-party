<?php

namespace App\Security;

use App\Model\GithubUser;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GithubUserProvider implements UserProviderInterface
{
    /**
     * @var array
     */
    private $roles;

    /**
     * @param array $roles
     */
    public function __construct(array $roles = ['ROLE_USER', 'ROLE_OAUTH_USER'])
    {
        $this->roles = $roles;
    }
    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        return new GithubUser($username, $this->roles);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof GithubUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $refreshedUser = $this->loadUserByUsername($user->getUsername());
        $refreshedUser->setAccessToken($user->getAccessToken());

        return $refreshedUser;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return GithubUser::class === $class;
    }
}
