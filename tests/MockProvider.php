<?php

declare(strict_types=1);

namespace OurEnergy\WitsApi\Tests;

use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use OurEnergy\WitsApi\Provider;

class MockProvider extends Provider
{
    public function getAccessToken($grant, array $options = []): AccessTokenInterface
    {
        return new AccessToken([
            "access_token" => "1234567890",
            "expires_in" => 7200,
            "token_type" => "bearer"
        ]);
    }
}