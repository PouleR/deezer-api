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
     * @return array|object
     */
    public function getUserInformation()
    {
        return $this->client->apiRequest('GET', 'user/me');
    }

    /**
     * @return array|object
     */
    public function getPermissions()
    {
        return $this->client->apiRequest('GET', 'user/me/permissions');
    }

    /**
     * @return array|object
     */
    public function getMyPlaylists()
    {
        return $this->client->apiRequest('GET', 'user/me/playlists');
    }

    /**
     * @return array|object
     */
    public function getMyAlbums()
    {
        return $this->client->apiRequest('GET', 'user/me/albums');
    }
}
