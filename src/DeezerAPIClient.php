<?php

namespace PouleR\DeezerAPI;

use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\RequestFactory;
use Psr\Http\Message\StreamInterface;

/**
 * Class DeezerAPIClient
  */
class DeezerAPIClient
{
    const DEEZER_API_URL = 'https://api.deezer.com';

    /**
     * Return types for json_decode
     */
    const RETURN_AS_OBJECT = 0;
    const RETURN_AS_ASSOC = 1;

    /**
     * @var PluginClient|HttpClient|null
     */
    protected $httpClient;

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var
     */
    protected $responseType = self::RETURN_AS_OBJECT;

    /**
     * DeezerAPIClient constructor.
     * @param HttpClient|null     $httpClient
     * @param RequestFactory|null $requestFactory
     */
    public function __construct(HttpClient $httpClient = null, RequestFactory $requestFactory = null)
    {
        if (!$httpClient) {
            $httpClient = new PluginClient(
                HttpClientDiscovery::find(),
                [new ErrorPlugin()]
            );
        }

        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory ?: MessageFactoryDiscovery::find();
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @param int $responseType
     */
    public function setResponseType($responseType)
    {
        $this->responseType = $responseType;
    }

    /**
     * @return int
     */
    public function getResponseType()
    {
        return $this->responseType;
    }

    /**
     * @param string                               $method
     * @param string                               $service
     * @param array                                $headers
     * @param resource|string|StreamInterface|null $body
     * @return object|array
     * @throws DeezerAPIException
     */
    public function apiRequest($method, $service, array $headers = [], $body = null)
    {
        $url = sprintf(
            '%s/%s?access_token=%s',
            self::DEEZER_API_URL,
            $service,
            $this->accessToken
        );

        try {
            $response = $this->httpClient->sendRequest(
                $this->requestFactory->createRequest($method, $url, $headers, $body)
            );
        } catch (\Exception $exception) {
            throw new DeezerAPIException(
                'API Request: '.$service.', '.$exception->getMessage(),
                $exception->getCode()
            );
        }

        return json_decode($response->getBody(), $this->responseType === self::RETURN_AS_ASSOC);
    }
}
