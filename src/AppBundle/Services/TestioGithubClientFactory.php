<?php

namespace AppBundle\Services;

use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class TestioGithubClientFactory
 * @package AppBundle\Services
 */
class TestioGithubClientFactory
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * TestioGithubClientFactory constructor.
     * @param $tokenStorage
     */
    public function __construct($tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return TestioGithubClient
     */
    public function create()
    {
        $client = new TestioGithubClient();

        /** @var OAuthToken $token */
        $token = $this->tokenStorage->getToken();

        $client->authenticate(
            $token->getAccessToken(),
            null,
            TestioGithubClient::AUTH_HTTP_TOKEN
        );
        return $client;
    }
}
