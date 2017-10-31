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
     * @param string $title
     * @return array|object
     * @throws DeezerAPIException
     */
    public function createPlaylist($title)
    {
        if (empty($title)) {
            throw new DeezerAPIException('Create playlist: invalid title');
        }

        return $this->client->apiRequest('POST', 'user/me/playlists', [], sprintf('title=%s', $title));
    }

    /**
     * @param string|int   $playlistId
     * @param string|array $trackIds
     * @return array|object
     * @throws DeezerAPIException
     */
    public function addTracksToPlaylist($playlistId, $trackIds)
    {
        if (empty($playlistId) || empty($trackIds)) {
            throw new DeezerAPIException('Add tracks to playlist: invalid parameters');
        }

        $trackIds = implode(',', (array) $trackIds);

        return $this->client->apiRequest('POST', 'playlist/'.$playlistId.'/tracks', [], sprintf('songs=%s', $trackIds));
    }

    /**
     * @return array|object
     */
    public function getMyAlbums()
    {
        return $this->client->apiRequest('GET', 'user/me/albums');
    }
}
