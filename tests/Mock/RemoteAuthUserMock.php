<?php

namespace Tests\Mock;

use RemoteAuthPhp\RemoteAuthUser;

class RemoteAuthUserMock implements RemoteAuthUser
{
    public function remoteAuthUserId(): string
    {
        return 'fake-user-id';
    }

    public function accessToken(): string
    {
        return 'fake-access-token';
    }

    public function refreshToken(): string
    {
        return 'fake-refresh-token';
    }

    public function accessTokenExpiration(): \DateTime
    {
        return new \DateTime();
    }

    public function handleTokenRefresh(string $userId, string $accessToken, string $refreshToken, int $expiresIn): void
    {
        return;
    }
}
