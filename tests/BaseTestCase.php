<?php

namespace OurEnergy\WitsApi\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    /**
     * @param string $body
     * @param int $status
     *
     * @return ClientInterface
     */
    protected function getMockHttpClient(string $body = "", int $status = 200): ClientInterface
    {
        $headers = [
            "Content-Type" => "application/json"
        ];

        $response = new Response($status, $headers, $body);

        $mock = new MockHandler([
            $response
        ]);

        $handler = HandlerStack::create($mock);

        return new Client(['handler' => $handler]);
    }
}