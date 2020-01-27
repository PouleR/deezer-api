<?php

namespace PouleR\DeezerAPI\Tests;

use PHPUnit\Framework\TestCase;
use PouleR\DeezerAPI\DeezerAPIClient;
use PouleR\DeezerAPI\DeezerAPIException;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * Class DeezerAPIClientTest.
  */
class DeezerAPIClientTest extends TestCase
{
    /**
     * @throws DeezerAPIException
     */
    public function testAPIRequest(): void
    {
        $mockResponse1 = new MockResponse('{"id": "12345","title": "Test"}');
        $mockResponse2 = new MockResponse('{"id": "54321","title": "Unit"}');
        $httpClient = new MockHttpClient([$mockResponse1, $mockResponse2]);
        $apiClient = new DeezerAPIClient($httpClient);

        $apiClient->setAccessToken('test.token');
        $response = $apiClient->apiRequest('GET', 'albums');
        self::assertInstanceOf(\stdClass::class, $response);

        $apiClient->setResponseType(DeezerAPIClient::RETURN_AS_ASSOC);
        $response = $apiClient->apiRequest('GET', 'tracks', ['header' => ['unit' => 'test']], 'Body');
        self::assertTrue(is_array($response));

        $requestOptions = $mockResponse2->getRequestOptions();
        self::assertContains('header: test', $requestOptions['headers']);
    }

    /**
     * @throws DeezerAPIException
     */
    public function testAPIRequestException(): void
    {
        $callback = function () {
            throw new TransportException('Deezer error', 500);
        };

        $httpClient = new MockHttpClient($callback);
        $apiClient = new DeezerAPIClient($httpClient);
        $apiClient->setAccessToken('test.token');

        $this->expectException(DeezerAPIException::class);
        $this->expectExceptionMessage('API Request: albums, Deezer error');
        $this->expectExceptionCode(500);
        $apiClient->apiRequest('GET', 'albums');
    }

    /**
     *
     */
    public function testResponseType(): void
    {
        $httpClient = new MockHttpClient();
        $apiClient = new DeezerAPIClient($httpClient);
        self::assertEquals(DeezerAPIClient::RETURN_AS_OBJECT, $apiClient->getResponseType());
        $apiClient->setResponseType(DeezerAPIClient::RETURN_AS_ASSOC);
        self::assertEquals(DeezerAPIClient::RETURN_AS_ASSOC, $apiClient->getResponseType());
    }
}
