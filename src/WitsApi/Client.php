<?php

declare(strict_types=1);

namespace OurEnergy\WitsApi;

use DateTimeInterface;
use Exception;
use GuzzleHttp\ClientInterface;
use Http\Client\Common\Exception\ClientErrorException;
use Http\Client\Common\Exception\ServerErrorException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessTokenInterface;
use OurEnergy\WitsApi\Enums\Island;
use OurEnergy\WitsApi\Enums\MarketType;
use OurEnergy\WitsApi\Enums\Schedule;
use OurEnergy\WitsApi\Models\Node;
use OurEnergy\WitsApi\Models\Price;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client
{
    const API_BASE = "https://api.electricityinfo.co.nz/api";

    protected ?AccessTokenInterface $accessToken = null;

    protected ClientInterface $httpClient;
    protected Provider $provider;
    protected bool $autoAuthenticate;

    /**
     * @param Provider $provider
     * @param bool $autoAuthenticate
     * @param ClientInterface|null $httpClient
     */
    public function __construct(Provider $provider, bool $autoAuthenticate = true, ClientInterface $httpClient = null)
    {
        $this->provider = $provider;
        $this->autoAuthenticate = $autoAuthenticate;

        if (is_null($httpClient)) {
            $this->httpClient = new \GuzzleHttp\Client();
        } else {
            $this->httpClient = $httpClient;
        }

        $provider->setHttpClient($this->httpClient);
    }

    /**
     * @return AccessTokenInterface|null
     */
    public function getAccessToken(): ?AccessTokenInterface
    {
        return $this->accessToken;
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    /**
     * @throws IdentityProviderException
     */
    public function authenticate(): void
    {
        $this->accessToken = $this->provider->getAccessToken("client_credentials");
    }

    /**
     * @throws IdentityProviderException
     */
    protected function autoAuthenticate(): void
    {
        if (!$this->autoAuthenticate) {
            return;
        }

        if (is_null($this->accessToken) || $this->accessToken->hasExpired()) {
            $this->authenticate();
        }
    }

    /**
     * @param string $path
     * @param string $method
     * @param array $query
     *
     * @throws ClientExceptionInterface
     * @throws IdentityProviderException
     *
     * @return ResponseInterface
     */
    protected function request(string $path, string $method = "GET", array $query = []): ResponseInterface
    {
        $this->autoAuthenticate();

        $queryString = http_build_query($query);
        $url = sprintf("%s%s?%s", self::API_BASE, $path, $queryString);

        $request = $this->provider->getAuthenticatedRequest($method, $url, $this->accessToken,
            [
                "headers" => [
                    "Content-Type" => "application/json",
                ]
            ]
        );

        $response = $this->httpClient->sendRequest($request);

        $this->processException($request, $response);

        return $response;
    }

    /**
     * Convert API responses to exceptions
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    protected function processException(RequestInterface $request, ResponseInterface $response): void
    {
        if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 500) {
            $data = $this->parseResponse($response);

            $message = match ($response->getStatusCode()) {
                default => sprintf("%s: %s", $data['code'], $data['message']),
                401 => sprintf("%s: %s", strtoupper($data['error']), $data['error_description']),
            };

            throw new ClientErrorException($message, $request, $response);
        }

        if ($response->getStatusCode() >= 500 && $response->getStatusCode() < 600) {
            throw new ServerErrorException($response->getReasonPhrase(), $request, $response);
        }
    }

    /**
     * Convert JSON responses to associative arrays
     *
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    protected function parseResponse(ResponseInterface $response): mixed
    {
        $contentType = $response->getHeaderLine("Content-type");
        [$contentType] = explode(";", $contentType);

        $content = (string)$response->getBody();

        return match ($contentType) {
            "application/json" => json_decode($content, true),
            default => $content,
        };
    }

    /**
     * Retrieve a list of GXP/GIP supported by this API
     *
     * @throws ClientExceptionInterface
     * @throws IdentityProviderException
     *
     * @return Node[]
     */
    public function getNodes(): array
    {
        $response = $this->request("/market-prices/v1/nodes");

        /** @var array $results */
        $results = $this->parseResponse($response);

        return array_map(fn (array $item) => Node::create($item), $results);
    }

    /**
     * Retrieve a list of prices for the given schedule
     *
     * @param Schedule $schedule
     * @param MarketType $marketType
     * @param array|null $nodes
     * @param DateTimeInterface|null $dateTimeFrom
     * @param DateTimeInterface|null $dateTimeTo
     * @param int|null $backPeriods
     * @param int|null $forwardPeriods
     * @param Island|null $island
     * @param int $offset

     * @throws ClientExceptionInterface
     * @throws IdentityProviderException
     * @throws Exception
     *
     * @return Price[]
     */
    public function getPrices(Schedule $schedule, MarketType $marketType, ?array $nodes = null, ?DateTimeInterface $dateTimeFrom = null, ?DateTimeInterface $dateTimeTo = null, ?int $backPeriods = null, ?int $forwardPeriods = null, ?Island $island = null, int $offset = 0): array
    {
        $response = $this->request("/market-prices/v1/schedules/{$schedule->value}/prices", "GET", [
            "marketType" => $marketType->value,
            "nodes" => $nodes ? implode(",", $nodes) : null,
            "from" => $dateTimeFrom?->format(DateTimeInterface::RFC3339),
            "to" => $dateTimeTo?->format(DateTimeInterface::RFC3339),
            "back" => $backPeriods,
            "forward" => $forwardPeriods,
            "island" => $island?->value,
            "offset" => $offset
        ]);

        /** @var array $results */
        $results = $this->parseResponse($response);

        return array_map(fn (array $item) => Price::create($item), $results['prices']);
    }
}