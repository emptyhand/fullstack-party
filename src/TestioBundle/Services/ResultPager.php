<?php

namespace TestioBundle\Services;

use Github\Api\ApiInterface;
use Github\ResultPager as BaseResultPager;

/**
 * Class ResultPager
 * @package TestioBundle\Services
 */
class ResultPager
{
    /**
     * @var BaseResultPager
     */
    private $basePager;

    /**
     * @var int
     */
    private $totalPagesCount;

    /**
     * @var array
     */
    private $result = [];

    /**
     * @var int
     */
    private $itemsPerPage;

    /**
     * ResultPager constructor.
     * @param BaseResultPager $pager
     */
    public function __construct(BaseResultPager $pager)
    {
        $this->basePager = $pager;
    }

    /**
     * @param ApiInterface $api
     * @param string $method
     * @param array $parameters
     * @return array
     */
    public function fetch(ApiInterface $api, $method, array $parameters = array())
    {
        // set current request items per page, to count results later
        $this->itemsPerPage = $api->getPerPage();

        // assign results, to count total items number later
        $this->result = $this->basePager->fetch($api, $method, $parameters);

        $pagination = $this->basePager->getPagination();
        // get total page count from last link
        if (isset($pagination['last'])) {
            preg_match('/page=(\d+).*$/', $pagination['last'], $matches);
            $this->totalPagesCount = (int)$matches[1];
        } elseif (isset($parameters[0]['page'])) {
            // if last link not exists then current page is last
            $this->totalPagesCount = $parameters[0]['page'];
        } else {
            // default value
            $this->totalPagesCount = 1;
        }

        return $this->result;
    }

    /**
     * @return int
     */
    public function getTotalPagesCount()
    {
        return $this->totalPagesCount;
    }

    /**
     * @return int
     */
    public function getTotalItemsCount()
    {
        $pagination = $this->basePager->getPagination();
        // if last page link exists, count last page items
        if (isset($pagination['last'])) {
            $lastPageItemsCount = count($this->basePager->fetchLast());
        } else {
            // if last page items not exist, count current result items
            $lastPageItemsCount = count($this->result);
        }
        return ($this->totalPagesCount - 1)  * $this->itemsPerPage + $lastPageItemsCount;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (!method_exists($this, $name)) {
            return call_user_func_array([$this->basePager, $name], $arguments);
        }
        return call_user_func_array([$this, $name], $arguments);
    }
}
