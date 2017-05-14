<?php

namespace AppBundle\Controller;

use AppBundle\Services\TestioIssueStats;
use AppBundle\Services\TestioResultPager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class IssueController
 * @package AppBundle\Controller
 */
class IssueController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Route("/filter/{state}", name="filter")
     * @param string $state
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, string $state = 'all')
    {
        $page = $request->query
            ->getInt('page', 1);

        $client = $this->get('app.github_client');

        $api = $client->api('issue');

        $resultPager = new TestioResultPager($client);

        // get all user issues
        $issues = $resultPager
            ->fetch($api, 'allUser', [
                ['state' => $state, 'page' => $page]
            ]);

        // create pagination
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($issues, $page, $api->getPerPage());
        $pagination->setTotalItemCount(
            $resultPager->getTotalItemsCount()
        );

        $issueStats = new TestioIssueStats($api, $resultPager);
        $stats = [
            'open' => $issueStats->getIssuesCount(['state' => 'open']),
            'closed' => $issueStats->getIssuesCount(['state' => 'closed']),
        ];

        return $this->render('issue/list.html.twig', [
            'pagination' => $pagination,
            'stats' => $stats,
            'issues' => $issues,
            'state' => $state,
        ]);
    }

    /**
     * @Route("/show/{owner}/{repo}/issues/{number}", name="show")
     * @param string $owner
     * @param string $repo
     * @param string $number
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(string $owner, string $repo, string $number)
    {
        $client = $this->get('app.github_client');

        $issue = $client->issues()
            ->show($owner, $repo, $number);

        $comments = $client->issues()
            ->comments()->all($owner, $repo, $number);

        return $this->render('issue/show.html.twig', [
            'issue' => $issue,
            'comments' => $comments
        ]);
    }
}
