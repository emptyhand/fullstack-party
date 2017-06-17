<?php

namespace TestioBundle\Services;

use Github\Client;

/**
 * Class GithubClient
 * @package TestioBundle\Services
 */
class GithubClient
{
    /**
     * @var Client
     */
    private $baseClient;

    /**
     * GithubClient constructor.
     * @param Client $baseClient
     */
    public function __construct(Client $baseClient)
    {
        $this->baseClient = $baseClient;
    }

    public function __call($name, $arguments)
    {
        if ($name === 'api' && in_array($arguments[0], ['issue', 'issues'], true)) {
            return new IssueApi($this->baseClient);
        }
        return call_user_func_array([$this->baseClient, $name], $arguments);
    }
}
