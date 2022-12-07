<?php

namespace PouleR\DeezerAPI;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
     * @var HttpClientInterface
     */
    protected $httpClient;

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
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @param int $responseType
     */
    public function setResponseType(int $responseType): void
    {
        $this->responseType = $responseType;
    }

    /**
     * @return int
     */
    public function getResponseType(): int
    {
        return $this->responseType;
    }

    /**
     * @param string                                      $method
     * @param string                                      $service
     * @param array                                       $headers
     * @param array|string|resource|\Traversable|\Closure $body
     * @param array|null                                  $query
     *
     * @return object|array
     *
     * @throws DeezerAPIException
     */
    public function apiRequest(string $method, string $service, array $headers = [], $body = null, $query = null)
    {
        $url = sprintf('%s/%s', self::DEEZER_API_URL, $service);

        if (null === $query) {
            $query = [];
        }

        $query['access_token'] = $this->accessToken;
        $url.= '?'.http_build_query($query);

        try {
            $response = $this->httpClient->request($method, $url, [
                'headers' => $headers,
                'body' => $body,
                'query' => $query
            ]);

            return json_decode($response->getContent(), $this->responseType === self::RETURN_AS_ASSOC);
        } catch (ServerExceptionInterface | ClientExceptionInterface | RedirectionExceptionInterface | TransportExceptionInterface $exception) {
            throw new DeezerAPIException(
                'API Request: '.$service.', '.$exception->getMessage(),
                $exception->getCode()
            );
        }
    }
}
