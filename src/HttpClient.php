<?php

namespace RemoteAuthPhp;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;

class HttpClient
{
    /** @var array */
    private $options;

    /** @var GuzzleClient */
    private $http;

    /** @var bool */
    private $attemptingRefresh = false;

    /**
     * Creates a new RemoteAuthPhp Client.
     * 
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
        
        $this->http = new GuzzleClient();
    }

    /**
     * Performs a synchronous GET request to the given URL.
     *
     * Returns the response
     *
     * @param string $url
     * @param RemoteAuthUser $user
     * @return array
     */
    public function get(string $url, RemoteAuthUser $user)
    {
        return $this->request('GET', $url, $user);
    }

    /**
     * Performs a synchronous POST request to the given URL.
     *
     * Returns the response
     *
     * @param string $url
     * @param RemoteAuthUser $user
     * @param array $payload
     * @return array
     */
    public function post(string $url, RemoteAuthUser $user, ?array $payload = [])
    {
        return $this->request('POST', $url, $user, $payload);
    }

    /**
     * Performs a synchronous PUT request to the given URL.
     *
     * Returns the response
     *
     * @param string $url
     * @param RemoteAuthUser $user
     * @param array $payload
     * @return array
     */
    public function put(string $url, RemoteAuthUser $user, ?array $payload = [])
    {
        return $this->request('PUT', $url, $user, $payload);
    }

    /**
     * Performs a synchronous DELETE request to the given URL.
     *
     * Returns the response
     *
     * @param string $url
     * @param RemoteAuthUser $user
     * @param array $payload
     * @return array
     */
    public function delete(string $url, RemoteAuthUser $user, ?array $payload = [])
    {
        return $this->request('DELETE', $url, $user, $payload);
    }

    /**
     * Makes a HTTP request.
     *
     * Adds Authentication header for authenticating the user.
     *
     * @param string $method
     * @param string $url
     * @param RemoteAuthUser $user
     * @param array $payload
     * @return array
     */
    public function request(string $method, string $url, RemoteAuthUser $user, ?array $payload = [])
    {
        try {
            $response = $this->http->request($method, $url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $user->accessToken()
                ],
                'json' => !empty($payload) ? $payload : null
            ]);
        } catch (ClientException $e) {
            if ($e->getCode() === 401 && !$this->attemptingRefresh) {
                $this->attemptingRefresh = true;
    
                // Request is unauthorized, use refresh token to get a new access token
                $response = $this->post(
                    $this->options['baseUrl'] . '/oauth/token',
                    $user,
                    [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $user->refreshToken(),
                        'client_id' => $this->options['clientId'],
                        'client_secret' => $this->options['clientSecret'],
                        'scope' => $this->options['scope']
                    ]
                );

                if (isset($response['error'])) {
                    throw new \Exception($response['message']);
                }
                
                $user->handleTokenRefresh(
                    $response['access_token'], $response['refresh_token'], $response['expires_in']
                );
    
                $this->attemptingRefresh = false;
    
                return $this->request($method, $url, $user, $payload);
            }

            return [
                'error' => true,
                'message' => (string)$e->getResponse()->getBody()
            ];
        }

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * Helper function to generate the URL to call.
     * 
     * @param string $url
     * @return string
     */
    public function url(string $url)
    {
        return $this->options['baseUrl'] . '/api/v1/' . $url;
    }
}
