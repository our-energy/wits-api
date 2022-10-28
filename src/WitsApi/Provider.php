<?php

declare(strict_types=1);

namespace OurEnergy\WitsApi;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Provider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public function getBaseAuthorizationUrl(): string
    {
        return "";
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return "https://api.electricityinfo.co.nz/login/oauth2/token";
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return "";
    }

    protected function getDefaultScopes()
    {

    }

    protected function checkResponse(ResponseInterface $response, $data)
    {

    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {

    }
}