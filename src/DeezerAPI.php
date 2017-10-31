<?php

namespace PouleR\DeezerAPI;

/**
 * Class DeezerAPI
 */
class DeezerAPI
{
    /**
     * @var DeezerAPIClient
     */
    protected $client;

    /**
     * DeezerAPI constructor.
     * @param DeezerAPIClient $client
     */
    public function __construct(DeezerAPIClient $client)
    {
        $this->client = $client;
    }

    /**
     *
     */
    public function getMyPlaylists()
    {
        return $this->client->apiRequest('GET', 'user/me/playlists');
    }
}
