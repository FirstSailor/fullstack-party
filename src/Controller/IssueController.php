<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class IssueController extends Controller
{
    /**
     * @Route("/{page}", name="app.issue.list_issues", defaults={"page": 1}, requirements={"page": "\d+"})
     * @Template("base.html.twig")
     *
     * @param int $page
     * @return array
     */
    public function listIssuesAction(int $page)
    {
        try {
            return [
                'pager' => $this->get('app.github.paginated_data_manager')->getIssuesPager($page)
            ];
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @Route(
     *     "/{owner}/{repo}/{number}",
     *     name="app.issue.single_issue",
     *     requirements={"owner": "\S+", "repo": "\S+", "number": "\d+"}
     * )
     * @Template("base.html.twig")
     *
     * @param string $owner
     * @param string $repo
     * @param int $number
     * @return array
     */
    public function singleIssueAction(string $owner, string $repo, int $number)
    {
        try {
            return [
                'data' => $this->get('app.github.repository')->getSingleIssue($owner, $repo, $number)
            ];
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @Route(
     *     "/{owner}/{repo}/{number}/comments",
     *     name="app.issue.issue_comments",
     *     requirements={"owner": "\S+", "repo": "\S+", "number": "\d+"}
     * )
     * @Template("base.html.twig")
     *
     * @param string $owner
     * @param string $repo
     * @param int $number
     * @return array
     */
    public function issueCommentsAction(string $owner, string $repo, int $number)
    {
        try {
            return [
                'data' => $this->get('app.github.repository')->getIssueComments($owner, $repo, $number)
            ];
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
}
