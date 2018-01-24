<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class GithubOauthController extends Controller
{
    /**
     * @Route("/github", name="app.github_oauth.connect")
     *
     * @return RedirectResponse
     */
    public function connectAction(): RedirectResponse
    {
        return $this->get('oauth2.registry')->getClient('github')->redirect(['user:email', 'public_repo']);
    }

    /**
     * @Route("/github/check", name="app.github_oauth.connect_check")
     */
    public function connectCheckAction()
    {
        // Let the App\Security\GithubOauthAuthenticator do its job.
    }
}
