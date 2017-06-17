<?php

namespace TestioBundle\Services;

use Github\Api\Issue;

/**
 * Class IssueApi
 * @package TestioBundle\Services
 */
class IssueApi extends Issue
{
    /**
     * Default items per page value
     * @var int
     */
    protected $perPage = 3;

    /**
     * List all issues assigned to the authenticated user across all visible
     * repositories including owned repositories, member repositories, and
     * organization repositories
     * https://developer.github.com/v3/issues/#list-issues
     * @param array $params
     * @return mixed
     */
    public function allUser($params)
    {
        if (isset($params['state']) &&
            !in_array($params['state'], ['open', 'closed', 'all'], true)) {
            return new \Exception('Wrong state params!');
        }

        return $this->get('/issues', array_merge(['page' => 1], $params));
    }
}
