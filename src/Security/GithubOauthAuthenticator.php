<?php

namespace App\Security;

use App\Model\GithubUser;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;

class GithubOauthAuthenticator extends SocialAuthenticator
{
    /**
     * @var ClientRegistry
     */
    protected $clientRegistry;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @param ClientRegistry $clientRegistry
     * @param RouterInterface $router
     */
    public function __construct(ClientRegistry $clientRegistry, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('login'));
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return $request->getPathInfo() == '/connect/github/check';
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/connect/github/check') {
            return;
        }

        return $this->fetchAccessToken($this->getClient());
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var GithubUser $user */
        $user = $userProvider->loadUserByUsername($this->getClient()->fetchUserFromToken($credentials)->getId());
        $user->setAccessToken($credentials);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new RedirectResponse($this->router->generate('login'));
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('app.issue.list_issues'));
    }

    /**
     * @return OAuth2Client
     */
    private function getClient(): OAuth2Client
    {
        return $this->clientRegistry->getClient('github');
    }
}
