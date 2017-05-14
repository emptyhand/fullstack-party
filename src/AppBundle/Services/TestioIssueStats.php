<?php

namespace AppBundle\Services;

/**
 * Class TestioIssueStats
 * @package AppBundle\Services
 */
class TestioIssueStats
{
    /**
     * @var TestioResultPager
     */
    private $pager;

    /**
     * @var TestioIssueApi
     */
    private $api;

    /**
     * TestioIssueStats constructor.
     * @param TestioIssueApi $api
     * @param TestioResultPager $pager
     */
    public function __construct(TestioIssueApi $api, TestioResultPager $pager)
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
