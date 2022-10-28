<?php

namespace OurEnergy\WitsApi\Tests;

use Http\Client\Common\Exception\ClientErrorException;
use OurEnergy\WitsApi\Client;

class ClientTest extends BaseTestCase
{
    public function testAutoAuthenticate(): void
    {
        $mockClient = $this->getMockHttpClient(file_get_contents(__DIR__ . '/mocks/GetNodes.json'));

        $provider = new MockProvider();

        $client = new Client($provider, true, $mockClient);

        $this->assertNull($client->getAccessToken());

        $client->getNodes();

        $this->assertNotNull($client->getAccessToken());
    }

    public function testUnauthenticated(): void
    {
        $mockClient = $this->getMockHttpClient(file_get_contents(__DIR__ . '/mocks/401.json'), 401);

        $provider = new MockProvider();

        $client = new Client($provider, false, $mockClient);

        $this->assertNull($client->getAccessToken());

        $this->expectException(ClientErrorException::class);
        $this->expectExceptionCode(401);
        $this->expectExceptionMessage("INVALID_REQUEST: The access token is missing");

        $client->getNodes();
    }
}
