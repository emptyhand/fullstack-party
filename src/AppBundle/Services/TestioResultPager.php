<?php

namespace AppBundle\Services;

use Github\Api\ApiInterface;
use Github\ResultPager;

/**
 * Class TestioResultPager
 * @package AppBundle\Services
 */
class TestioResultPager extends ResultPager
{
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
        $this->result = parent::fetch($api, $method, $parameters);

        // get total page count from last link
        if (isset($this->pagination['last'])) {
            preg_match('/page=(\d+).*$/', $this->pagination['last'], $matches);
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
        // if last page link exists, count last page items
        if (isset($this->pagination['last'])) {
            $lastPageItemsCount = count($this->fetchLast());
        } else {
            // if last page items not exist, count current result items
            $lastPageItemsCount = count($this->result);
        }
        return ($this->totalPagesCount - 1)  * $this->itemsPerPage + $lastPageItemsCount;
    }
}
