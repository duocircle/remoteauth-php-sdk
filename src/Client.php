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
     * @param bool $ignoreCache
     * @return array
     */
    public function applicationMembersByToken(RemoteAuthUser $user, ?bool $ignoreCache = false)
    {
        return $this->httpClient->get(
            $this->httpClient->url('users/applicationMembers/byToken'),
            $user,
            $ignoreCache
        );
    }

    /**
     * Returns the Permissions attached to the given ApplicationMember.
     *
     * @param string $applicationMemberId
     * @param RemoteAuthUser $user
     * @param bool $ignoreCache
     * @return array
     */
    public function permissionsByApplicationMember(string $applicationMemberId, RemoteAuthUser $user, ?bool $ignoreCache = false)
    {
        return $this->httpClient->get(
            $this->httpClient->url("applicationMembers/${applicationMemberId}/permissions"),
            $user,
            $ignoreCache
        );
    }

    /**
     * Returns the Roles attached to the given ApplicationMember.
     *
     * @param string $applicationMemberId
     * @param RemoteAuthUser $user
     * @param bool $ignoreCache
     * @return array
     */
    public function rolesByApplicationMember(string $applicationMemberId, RemoteAuthUser $user, ?bool $ignoreCache = false)
    {
        return $this->httpClient->get(
            $this->httpClient->url("applicationMembers/${applicationMemberId}/roles"),
            $user,
            $ignoreCache
        );
    }
}
