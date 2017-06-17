<?php

namespace TestioBundle\Services;

/**
 * Class IssueStats
 * @package TestioBundle\Services
 */
class IssueStats
{
    /**
     * @var ResultPager
     */
    private $pager;

    /**
     * @var IssueApi
     */
    private $api;

    /**
     * TestioIssueStats constructor.
     * @param IssueApi $api
     * @param ResultPager $pager
     */
    public function __construct(IssueApi $api, ResultPager $pager)
    {
        $this->api = $api;
        $this->pager = $pager;
    }

    /**
     * @param array $params
     * @return int
     */
    public function getIssuesCount(array $params = [])
    {
        $this->pager->fetch($this->api, 'allUser', [$params]);
        return $this->pager->getTotalItemsCount();
    }
}
