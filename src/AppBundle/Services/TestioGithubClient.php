<?php

namespace AppBundle\Services;

use Github\Client;

/**
 * Class TestioGithubClient
 * @package AppBundle\Services
 */
class TestioGithubClient extends Client
{
    /**
     * @param string $name
     * @return \Github\Api\ApiInterface
     */
    public function api($name)
    {
        // extending issue api
        if (in_array($name, ['issue', 'issues'])) {
            return new TestioIssueApi($this);
        }
        return parent::api($name);
    }
}
