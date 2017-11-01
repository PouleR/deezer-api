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
     * @return DeezerAPIClient
     */
    public function getDeezerAPIClient()
    {
        return $this->client;
    }

    /**
     * Return the user data.
     * @return array|object
     */
    public function getUserInformation()
    {
        return $this->client->apiRequest('GET', 'user/me');
    }

    /**
     * Return the user's Permissions granted to the application.
     * @return array|object
     */
    public function getPermissions()
    {
        return $this->client->apiRequest('GET', 'user/me/permissions');
    }

    /**
     * Return a list of user's public playlist.
     * @return array|object
     */
    public function getMyPlaylists()
    {
        return $this->client->apiRequest('GET', 'user/me/playlists');
    }

    /**
     * Create a playlist.
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
     * Add tracks to a playlist.
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
     * Return a list of user's favorite albums.
     * @return array|object
     */
    public function getMyAlbums()
    {
        return $this->client->apiRequest('GET', 'user/me/albums');
    }

    /**
     * Return a list of album's tracks.
     * @param string|int $albumId
     * @return array|object
     * @throws DeezerAPIException
     */
    public function getAlbumTracks($albumId)
    {
        if (empty($albumId)) {
            throw new DeezerAPIException('Get album tracks: invalid albumId');
        }

        return $this->client->apiRequest('GET', 'album/'.$albumId.'/tracks');
    }
}
