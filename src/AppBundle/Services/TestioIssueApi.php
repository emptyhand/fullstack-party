<?php

namespace AppBundle\Services;

use Github\Api\Issue;
use Http\Discovery\Exception\NotFoundException;

/**
 * Class TestioIssueApi
 * @package AppBundle\Services
 */
class TestioIssueApi extends Issue
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
            !in_array($params['state'], ['open', 'closed', 'all'])) {
            throw new NotFoundException('Invalid state');
        }

        return $this->get('/issues', array_merge(['page' => 1], $params));
    }
}
