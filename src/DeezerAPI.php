<?php

namespace PouleR\DeezerAPI;

/**
 * Class DeezerAPI.
 */
class DeezerAPI
{
    /**
     * @var DeezerAPIClient
     */
    protected DeezerAPIClient $client;

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
    public function getDeezerAPIClient(): DeezerAPIClient
    {
        return $this->client;
    }

    /**
     * Return the user data.
     *
     * @throws DeezerAPIException
     *
     * @return array|object
     */
    public function getUserInformation()
    {
        return $this->client->apiRequest('GET', 'user/me');
    }

    /**
     * Return the user's Permissions granted to the application.
     *
     * @throws DeezerAPIException
     *
     * @return array|object
     */
    public function getPermissions()
    {
        return $this->client->apiRequest('GET', 'user/me/permissions');
    }

    /**
     * Return a list of user's public playlist.
     *
     * @throws DeezerAPIException
     *
     * @return array|object
     */
    public function getMyPlaylists()
    {
        return $this->client->apiRequest('GET', 'user/me/playlists');
    }

    /**
     * Create a playlist.
     *
     * @param string $title
     *
     * @return array|object
     *(
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
     *
     * @param string|int   $playlistId
     * @param string|array $trackIds
     *
     * @return array|object
     *
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
     *
     * @throws DeezerAPIException
     *
     * @return array|object
     */
    public function getMyAlbums()
    {
        return $this->client->apiRequest('GET', 'user/me/albums');
    }

    /**
     * Return a list of album's tracks.
     *
     * @param string|int $albumId
     *
     * @return array|object
     *
     * @throws DeezerAPIException
     */
    public function getAlbumTracks($albumId)
    {
        if (empty($albumId)) {
            throw new DeezerAPIException('Get album tracks: invalid albumId');
        }

        return $this->client->apiRequest('GET', 'album/'.$albumId.'/tracks');
    }

    /**
     * Add an artist to the user's favorites.
     *
     * @param string|int $artistId
     *
     * @return array|object
     *
     * @throws DeezerAPIException
     */
    public function addArtistToFavorites($artistId)
    {
        if (empty($artistId)) {
            throw new DeezerAPIException('Favorite artist: invalid artistId');
        }

        return $this->client->apiRequest('POST', 'user/me/artists', [], sprintf('artist_id=%s', $artistId));
    }


    /**
     * Add an album to the user's library.
     *
     * @param string|int $albumId
     *
     * @return array|object
     *(
     * @throws DeezerAPIException
     */
    public function addAlbumToLibrary($albumId)
    {
        if (empty($albumId)) {
            throw new DeezerAPIException('Album library: invalid albumId');
        }

        return $this->client->apiRequest('POST', 'user/me/albums', [], sprintf('album_id=%s', $albumId));
    }

    /**
     * Add a track to the user's favorites.
     *
     * @param string|int $trackId
     *
     * @return array|object
     *(
     * @throws DeezerAPIException
     */
    public function addTrackToFavorites($trackId)
    {
        if (empty($trackId)) {
            throw new DeezerAPIException('Track favorites: invalid trackId');
        }

        return $this->client->apiRequest('POST', 'user/me/tracks', [], sprintf('track_id=%s', $trackId));
    }

    /**
     * Follow user.
     *
     * @param string|int $userId
     *
     * @return array|object
     *
     * @throws DeezerAPIException
     */
    public function followUser($userId)
    {
        if (empty($userId)) {
            throw new DeezerAPIException('Follow user: invalid userId');
        }

        return $this->client->apiRequest('POST', 'user/me/followings', [], sprintf('user_id=%s', $userId));
    }

    /**
     * Add a playlist to the user's favorites.
     *
     * @param string|int $playlistId
     *
     * @return array|object
     *
     * @throws DeezerAPIException
     */
    public function addPlaylistToFavorites($playlistId)
    {
        if (empty($playlistId)) {
            throw new DeezerAPIException('Favorite playlist: invalid playlistId');
        }

        return $this->client->apiRequest('POST', 'user/me/playlists', [], sprintf('playlist_id=%s', $playlistId));
    }

    /**
     * @param string      $query
     * @param bool        $strict
     * @param string|null $order
     * @param string|null $type
     *
     * @return array|object
     *
     * @throws DeezerAPIException
     */
    public function search(string $query, bool $strict = false, string $order = null, ?string $type = null): object|array
    {
        if (empty($query)) {
            throw new DeezerAPIException('A query parameter is mandatory');
        }

        $apiQuery = ['q' => $query];

        if (true === $strict) {
            $apiQuery['strict'] = 'on';
        }

        if ($order) {
            $apiQuery['order'] = $order;
        }

        $endpoint = 'search';
        if (null !== $type) {
            $endpoint.= '/'.urlencode($type);
        }

        return $this->client->apiRequest('GET', $endpoint, [], null, $apiQuery);
    }
}
