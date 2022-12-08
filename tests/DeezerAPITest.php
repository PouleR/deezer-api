<?php

namespace PouleR\DeezerAPI\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PouleR\DeezerAPI\DeezerAPI;
use PouleR\DeezerAPI\DeezerAPIClient;
use PouleR\DeezerAPI\DeezerAPIException;

/**
 * Class DeezerAPITest
 */
class DeezerAPITest extends TestCase
{
    /**
     * @var DeezerAPIClient|MockObject
     */
    private $client;

    /**
     * @var DeezerAPI
     */
    private $deezerApi;

    /**
     *
     */
    public function setUp(): void
    {
        $this->client = $this->createMock(DeezerAPIClient::class);
        $this->deezerApi = new DeezerAPI($this->client);
    }

    /**
     *
     */
    public function testDeezerAPIClient(): void
    {
        self::assertEquals($this->client, $this->deezerApi->getDeezerAPIClient());
    }

    /**
     * @throws DeezerAPIException
     */
    public function testUserInformation(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'user/me')
            ->willReturn('{"name": "test"}');

        self::assertEquals('{"name": "test"}', $this->deezerApi->getUserInformation());
    }

    /**
     * @throws DeezerAPIException
     */
    public function testPermissions(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'user/me/permissions')
            ->willReturn('{"permissions":{"basic_access":true}}');

        self::assertEquals('{"permissions":{"basic_access":true}}', $this->deezerApi->getPermissions());
    }

    /**
     * @throws DeezerAPIException
     */
    public function testMyPlaylists(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'user/me/playlists')
            ->willReturn('{}');

        self::assertEquals('{}', $this->deezerApi->getMyPlaylists());
    }

    /**
     * @throws DeezerAPIException
     */
    public function testCreatePlaylistEmptyTitle(): void
    {
        $this->expectException(DeezerAPIException::class);
        $this->expectExceptionMessage('Create playlist: invalid title');
        $this->deezerApi->createPlaylist('');
    }

    /**
     * @throws DeezerAPIException
     */
    public function testCreatePlaylist(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('POST', 'user/me/playlists', [], 'title=New playlist')
            ->willReturn(['id' => '100']);

        self::assertEquals(['id' => '100'], $this->deezerApi->createPlaylist('New playlist'));
    }

    /**
     * @throws DeezerAPIException
     */
    public function testAddTracksToPlaylistEmptyPlaylist(): void
    {
        $this->expectException(DeezerAPIException::class);
        $this->expectExceptionMessage('Add tracks to playlist: invalid parameters');
        $this->deezerApi->addTracksToPlaylist('', 'songs');
    }

    /**
     * @throws DeezerAPIException
     */
    public function testAddTracksToPlaylistEmptyTracks(): void
    {
        $this->expectException(DeezerAPIException::class);
        $this->expectExceptionMessage('Add tracks to playlist: invalid parameters');
        $this->deezerApi->addTracksToPlaylist('playlist', []);
    }

    /**
     * @throws DeezerAPIException
     */
    public function testAddTracksToPlaylist(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('POST', 'playlist/12345/tracks', [], 'songs=unit,test,song')
            ->willReturn('OK');

        self::assertEquals('OK', $this->deezerApi->addTracksToPlaylist(12345, ['unit', 'test', 'song']));
    }

    /**
     * @throws DeezerAPIException
     */
    public function testMyAlbums(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'user/me/albums')
            ->willReturn('{"data":[]}');

        self::assertEquals('{"data":[]}', $this->deezerApi->getMyAlbums());
    }

    /**
     * @throws DeezerAPIException
     */
    public function testAlbumTracksEmptyAlbum(): void
    {
        $this->expectException(DeezerAPIException::class);
        $this->expectExceptionMessage('Get album tracks: invalid albumId');
        $this->deezerApi->getAlbumTracks('');
    }

    /**
     * @throws DeezerAPIException
     */
    public function testAlbumTracks(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'album/1234/tracks')
            ->willReturn('{}');

        self::assertEquals('{}', $this->deezerApi->getAlbumTracks(1234));
    }

    /**
     * @throws DeezerAPIException
     */
    public function testArtistToFavoritesEmptyArtist(): void
    {
        $this->expectException(DeezerAPIException::class);
        $this->expectExceptionMessage('Favorite artist: invalid artistId');
        $this->deezerApi->addArtistToFavorites('');
    }

    /**
     * @throws DeezerAPIException
     */
    public function testArtistToFavorites(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('POST', 'user/me/artists', [], 'artist_id=artist')
            ->willReturn('{}');

        self::assertEquals('{}', $this->deezerApi->addArtistToFavorites('artist'));
    }

    /**
     * @throws DeezerAPIException
     */
    public function testAlbumToLibraryEmptyAlbumId(): void
    {
        $this->expectException(DeezerAPIException::class);
        $this->expectExceptionMessage('Album library: invalid albumId');
        $this->deezerApi->addAlbumToLibrary('');
    }

    /**
     * @throws DeezerAPIException
     */
    public function testAlbumToLibrary(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('POST', 'user/me/albums', [], 'album_id=789')
            ->willReturn('{}');

        self::assertEquals('{}', $this->deezerApi->addAlbumToLibrary('789'));
    }

    /**
     * @throws DeezerAPIException
     */
    public function testTrackToFavoritesEmptyTrackId(): void
    {
        $this->expectException(DeezerAPIException::class);
        $this->expectExceptionMessage('Track favorites: invalid trackId');
        $this->deezerApi->addTrackToFavorites('');
    }

    /**
     * @throws DeezerAPIException
     */
    public function testTrackToFavorites(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('POST', 'user/me/tracks', [], 'track_id=12345')
            ->willReturn('{}');

        self::assertEquals('{}', $this->deezerApi->addTrackToFavorites('12345'));
    }

    /**
     * @throws DeezerAPIException
     */
    public function testFollowUserEmptyUser(): void
    {
        $this->expectException(DeezerAPIException::class);
        $this->expectExceptionMessage('Follow user: invalid userId');
        $this->deezerApi->followUser(null);
    }

    /**
     * @throws DeezerAPIException
     */
    public function testFollowUser(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('POST', 'user/me/followings', [], 'user_id=user')
            ->willReturn('{}');

        self::assertEquals('{}', $this->deezerApi->followUser('user'));
    }

    /**
     * @throws DeezerAPIException
     */
    public function testPlaylistToFavoritesEmptyPlaylist(): void
    {
        $this->expectException(DeezerAPIException::class);
        $this->expectExceptionMessage('Favorite playlist: invalid playlistId');
        $this->deezerApi->addPlaylistToFavorites(0);
    }

    /**
     * @throws DeezerAPIException
     */
    public function testPlaylistToFavorites(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('POST', 'user/me/playlists', [], 'playlist_id=1234')
            ->willReturn('{}');

        self::assertEquals('{}', $this->deezerApi->addPlaylistToFavorites(1234));
    }

    /***
     * @throws DeezerAPIException
     */
    public function testInvalidSearchQuery(): void
    {
        $this->expectException(DeezerAPIException::class);
        $this->expectExceptionMessage('A query parameter is mandatory');
        $this->deezerApi->search('');
    }

    /**
     * @throws DeezerAPIException
     */
    public function testSearch(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'search', [], null, ['q' => 'bohemian', 'strict' => 'on', 'order' => 'RANKING'])
            ->willReturn([]);
        $this->deezerApi->search('bohemian', true, 'RANKING');
    }

    /**
     * @throws DeezerAPIException
     */
    public function testSearchArtist(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'search/artist', [], null, ['q' => 'bohemian', 'strict' => 'on', 'order' => 'RANKING'])
            ->willReturn([]);
        $this->deezerApi->search('bohemian', true, 'RANKING', 'artist');
    }
}
