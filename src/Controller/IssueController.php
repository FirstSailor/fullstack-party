<?php

namespace App\Controller;

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


        return [];
    }
}
