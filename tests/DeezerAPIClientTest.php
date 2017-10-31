<?php

namespace PouleR\DeezerAPI\Tests;

use GuzzleHttp\Psr7\Response;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use PouleR\DeezerAPI\DeezerAPIClient;

/**
 * Class DeezerAPIClientTest
  */
class DeezerAPIClientTest extends TestCase
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var DeezerAPIClient
     */
    private $client;

    /**
     *
     */
    public function setUp()
    {
        $this->httpClient = new Client();
        $this->client = new DeezerAPIClient($this->httpClient);
    }

    /**
     *
     */
    public function testAPIRequest()
    {
        $this->client->setAccessToken('test.token');
        $this->httpClient->addResponse(new Response(200, [], '{"id": "12345","title": "Test"}'));
        $this->httpClient->addResponse(new Response(200, [], '{"id": "54321","title": "Unit"}'));

        $response = $this->client->apiRequest('GET', 'albums');
        self::assertInstanceOf(\stdClass::class, $response);

        $this->client->setResponseType(DeezerAPIClient::RETURN_AS_ASSOC);
        $response = $this->client->apiRequest('GET', 'tracks', ['header' => ['unit' => 'test']], 'Body');
        self::assertTrue(is_array($response));

        $requests = $this->httpClient->getRequests();
        self::assertCount(2, $requests);
        self::assertEquals('GET', $requests[0]->getMethod());
        self::assertEquals('api.deezer.com', $requests[0]->getUri()->getHost());
        self::assertEquals('/albums?access_token=test.token', $requests[0]->getRequestTarget());
        self::assertEquals('/tracks?access_token=test.token', $requests[1]->getRequestTarget());
        self::assertEquals(['unit' => 'test'], $requests[1]->getHeader('header'));
        self::assertEquals('Body', $requests[1]->getBody());
    }

    /**
     * @expectedException \PouleR\DeezerAPI\DeezerAPIException
     * @expectedExceptionMessage API Request: albums, Deezer error
     * @expectedExceptionCode 500
     */
    public function testAPIRequestException()
    {
        $this->client->setAccessToken('test.token');
        $this->httpClient->addException(new \Exception('Deezer error', 500));
        $this->client->apiRequest('GET', 'albums');
    }
}
