<?php

namespace App\Controller;

use App\Model\Github\IssueState;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class IssueController extends Controller
{
    /**
     * @Route(
     *     "/issues/{page}",
     *     name="app.issue.list_issues",
     *     defaults={"page": 1},
     *     requirements={"page": "\d+"}
     * )
     * @Template("issue/list/index.html.twig")
     * @Method("GET")
     *
     * @param int $page
     * @return array
     */
    public function listIssuesAction(int $page)
    {
        try {
            $dataManager = $this->get('app.github.paginated_data_manager');

            return [
                'pager' => $dataManager->getIssuesPager($page),
                'openCount' => $dataManager->getIssuesCountByState(IssueState::OPEN),
                'closedCount' => $dataManager->getIssuesCountByState(IssueState::CLOSED),
            ];
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @Route(
     *     "/issue/{owner}/{repo}/{number}",
     *     name="app.issue.single_issue",
     *     requirements={"owner": "\S+", "repo": "\S+", "number": "\d+"}
     * )
     * @Template("issue/entry/index.html.twig")
     * @Method("GET")
     *
     * @param string $owner
     * @param string $repo
     * @param int $number
     * @return array
     */
    public function issueAction(string $owner, string $repo, int $number)
    {
        try {
            $dataManager = $this->get('app.github.data_manager');

            return [
                'issue' => $dataManager->getSingleIssue($owner, $repo, $number),
                'comments' => $dataManager->getIssueComments($owner, $repo, $number),
            ];
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
}
