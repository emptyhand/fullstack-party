<?php

namespace TestioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Knp\Component\Pager\PaginatorInterface;
use TestioBundle\Services\IssueStats;
use Symfony\Component\HttpFoundation\Request;
use TestioBundle\Services\GithubClient;
use TestioBundle\Services\ResultPager;

/**
 * Class IssueController
 * @package TestioBundle\Controller
 */
class IssueController
{
    /**
     * @var GithubClient
     */
    private $client;

    /**
     * @var ResultPager
     */
    private $resultPager;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * IssueController constructor.
     * @param GithubClient $client
     * @param ResultPager $resultPager
     * @param PaginatorInterface $paginator
     * @param EngineInterface $templating
     */
    public function __construct(
        GithubClient $client,
        ResultPager $resultPager,
        PaginatorInterface $paginator,
        EngineInterface $templating
    ) {
        $this->client = $client;
        $this->resultPager = $resultPager;
        $this->paginator = $paginator;
        $this->templating = $templating;
    }

    /**
     * @param Request $request
     * @param string $state
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, string $state = 'all')
    {
        $page = $request->query
            ->getInt('page', 1);

        $api = $this->client->api('issue');

        // get all user issues
        $issues = $this->resultPager
            ->fetch($api, 'allUser', [
                ['state' => $state, 'page' => $page]
            ]);

        // create pagination
        $pagination = $this->paginator->paginate($issues, $page, $api->getPerPage());
        $pagination->setTotalItemCount(
            $this->resultPager->getTotalItemsCount()
        );

        $issueStats = new IssueStats($api, $this->resultPager);
        $stats = [
            'open' => $issueStats->getIssuesCount(['state' => 'open']),
            'closed' => $issueStats->getIssuesCount(['state' => 'closed']),
        ];

        return $this->templating
            ->renderResponse('@Testio/issue/list.html.twig', [
                'pagination' => $pagination,
                'stats' => $stats,
                'issues' => $issues,
                'state' => $state,
            ]);
    }

    /**
     * @param string $owner
     * @param string $repo
     * @param string $number
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(string $owner, string $repo, string $number)
    {
        $issue = $this->client->issues()
            ->show($owner, $repo, $number);

        $comments = $this->client->issues()
            ->comments()->all($owner, $repo, $number);

        return $this->templating
            ->renderResponse('@Testio/issue/show.html.twig', [
                'issue' => $issue,
                'comments' => $comments
            ]);
    }
}
