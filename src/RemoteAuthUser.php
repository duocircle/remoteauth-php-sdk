<?php

namespace RemoteAuthPhp;

interface RemoteAuthUser
{
    /**
     * Returns the User's access token.
     *
     * @return string
     */
    public function oauthAccessToken(): string;

    /**
     * Returns the User's refresh token.
     *
     * @return string
     */
    public function oauthRefreshToken(): string;

    /**
     * Returns the date the access token expires.
     *
     * @return DateTime
     */
    public function oauthAccessTokenExpiration(): \DateTime;

    /**
     * Callback function that will be called when refreshing the access token.
     * 
     * @param string $accessToken
     * @param string $refreshToken
     * @param int $expiresIn
     * @return void
     */
    public function handleTokenRefresh(string $accessToken, string $refreshToken, int $expiresIn): void;
}