<?php

namespace OurEnergy\WitsApi\Tests;

use OurEnergy\WitsApi\Client;

class NodesTest extends BaseTestCase
{
    public function testGetNodes(): void
    {
        $mockClient = $this->getMockHttpClient(file_get_contents(__DIR__ . '/mocks/GetNodes.json'));

        $provider = new MockProvider();

        $client = new Client($provider, true, $mockClient);

        $nodes = $client->getNodes();

        $this->assertCount(241, $nodes);

        $this->assertEquals("ABY0111", $nodes[0]->getNode());
        $this->assertEquals("SI", $nodes[0]->getIsland());
    }
}
