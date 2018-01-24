<?php

namespace App\Controller;

use App\Client\GithubResponseMediator;
use App\Repository\GithubRepository;
use KnpU\OAuth2ClientBundle\Security\User\OAuthUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class IssueController extends Controller
{
    /**
     * @Route("/list", name="app.issue.list")
     * @Template("base.html.twig")
     *
     * @return array
     */
    public function listAction(): array
    {

        try {
            $out = $this->get('app.github.repository')->getPaginatedIssues();

            dump($out);die;

            dump(GithubResponseMediator::getContent($out), GithubResponseMediator::getPagination($out));
            die;
        } catch (\Exception $e) {
            dump($e);die;
        }
        return [];
    }
}
