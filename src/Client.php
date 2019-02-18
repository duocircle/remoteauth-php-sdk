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
     * !! Application Members
     */

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
     * Returns the ApplicationMember (Team <> Application) relationships
     * between the given Application and the Teams the User is a member of.
     *
     * @param RemoteAuthUser $user
     * @param string $applicationId
     * @param bool $ignoreCache
     * @return array
     */
    public function teamMembershipsByApplication(RemoteAuthUser $user, string $applicationId, ?bool $ignoreCache = false)
    {
        return $this->httpClient->get(
            $this->httpClient->url("users/applicationMembers/teamMemberships/{$applicationId}"),
            $user,
            $ignoreCache
        );
    }

    /**
     * Creates a new ApplicationMember. Payload accepts:
     *
     * - application_id
     * - user_id (optional)
     * - team_id (optional)
     *
     * At least one of `user_id` or `team_id` must be specified.
     *
     * @param RemoteAuthUser $user
     * @param array $payload
     * @return array
     */
    public function createApplicationMember(RemoteAuthUser $user, array $payload = [])
    {
        return $this->httpClient->post(
            $this->httpClient->url('applicationMembers'),
            $user,
            $payload,
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

    /**
     * !! Teams
     */

    /**
     * Lists the Teams the User is a member of.
     *
     * @param RemoteAuthUser $user
     * @param bool $ignoreCache
     * @return array
     */
    public function teams(RemoteAuthUser $user, ?bool $ignoreCache = false)
    {
        return $this->httpClient->get(
            $this->httpClient->url('teams'),
            $user,
            $ignoreCache
        );
    }

    /**
     * Creates a new Team. Payload accepts:
     *
     * - name
     * - slug
     *
     * @param RemoteAuthUser $user
     * @param array $payload
     * @return array
     */
    public function createTeam(RemoteAuthUser $user, array $payload = [])
    {
        return $this->httpClient->post(
            $this->httpClient->url('teams'),
            $user,
            $payload,
            $ignoreCache
        );
    }
}
