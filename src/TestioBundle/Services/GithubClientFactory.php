<?php

namespace TestioBundle\Services;

use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Github\Client;

/**
 * Class GithubClientFactory
 * @package TestioBundle\Services
 */
class GithubClientFactory
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var Client
     */
    private $baseClient;

    /**
     * GithubClientFactory constructor.
     * @param $tokenStorage
     * @param Client $baseClient
     */
    public function __construct($tokenStorage, Client $baseClient)
    {
        $this->tokenStorage = $tokenStorage;
        $this->baseClient = $baseClient;
    }

    /**
     * @return GithubClient
     */
    public function create()
    {
        $client = new GithubClient($this->baseClient);

        /** @var OAuthToken $token */
        $token = $this->tokenStorage->getToken();

        $client->authenticate(
            $token->getAccessToken(),
            null,
            Client::AUTH_HTTP_TOKEN
        );
        return $client;
    }
}
