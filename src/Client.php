<?php

namespace RemoteAuthPhp;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use Psr\SimpleCache\CacheInterface;

class Client
{
    /** @var HttpClient */
    private $httpClient;

    /**
     * Creates a new RemoteAuthPhp Client.
     *
     * @param array $options
     */
    public function __construct(array $options = [], ?CacheInterface $cache = null)
    {
        $this->options = array_merge([
            'baseUrl' => 'https://app.remoteauth.com',
            'clientId' => null,
            'clientSecret' => null,
            'scope' => ''
        ], $options);

        $this->httpClient = new HttpClient($this->options, $cache);
    }

    /**
     * Returns the ApplicationMember relationships between
     * the token's User and the Application the token belongs to.
     *
     * @param RemoteAuthUser $user
     * @return array
     */
    public function applicationMembersByToken(RemoteAuthUser $user)
    {
        return $this->httpClient->get(
            $this->httpClient->url('users/applicationMembers/byToken'),
            $user
        );
    }
}
