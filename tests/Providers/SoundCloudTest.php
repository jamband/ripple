<?php

/*
 * This file is part of the ripple library.
 *
 * (c) Tomoki Morita <tmsongbooks215@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Providers;

use Jamband\Ripple\Ripple;
use PHPUnit\Framework\TestCase;

class SoundCloudTest extends TestCase
{
    private const URL_TRACK = 'https://soundcloud.com/foo/title';
    private const URL_PLAYLIST = 'https://soundcloud.com/foo/sets/title';

    public function testUrlWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK);
        $this->assertSame(self::URL_TRACK, $ripple->url());
    }

    public function testUrlWithPlaylistUrl(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame(self::URL_PLAYLIST, $ripple->url());
    }

    public function testIdWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="twitter:player" content="https://w.soundcloud.com/player/?url=https%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F123&amp;auto_play=false&amp;show_artwork=true&amp;visual=true&amp;origin=twitter">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('123', $ripple->id());
    }

    public function testIdWithPlaylistUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="twitter:player" content="https://w.soundcloud.com/player/?url=https%3A%2F%2Fapi.soundcloud.com%2Fplaylists%2F456&amp;auto_play=false&amp;show_artwork=true&amp;visual=true&amp;origin=twitter">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame('456', $ripple->id());
    }

    public function testTitleWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="og:title" content="Foo Title">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('Foo Title', $ripple->title());
    }

    public function testTitleWithPlaylistUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="og:title" content="Foo Album">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame('Foo Album', $ripple->title());
    }

    public function testImageWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="og:image" content="https://image.example.com/123.jpg">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('https://image.example.com/123.jpg', $ripple->image());
    }

    public function testImageWithPlaylistUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="og:image" content="https://image.example.com/456.jpg">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame('https://image.example.com/456.jpg', $ripple->image());
    }

    public function testEmbedWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="twitter:player" content="https://w.soundcloud.com/player/?url=https%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F123&amp;auto_play=false&amp;show_artwork=true&amp;visual=true&amp;origin=twitter">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/123', $ripple->embed());
    }

    public function testEmbedWithPlaylistUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="twitter:player" content="https://w.soundcloud.com/player/?url=https%3A%2F%2Fapi.soundcloud.com%2Fplaylists%2F456&amp;auto_play=false&amp;show_artwork=true&amp;visual=true&amp;origin=twitter">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame('https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/456', $ripple->embed());
    }

    public function testEmbedWithTrackUrlAndOptions(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="twitter:player" content="https://w.soundcloud.com/player/?url=https%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F123&amp;auto_play=false&amp;show_artwork=true&amp;visual=true&amp;origin=twitter">';
        $ripple->options(['response' => $response, 'embed' => ['SoundCloud' => 'show_comments=false']]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/123&show_comments=false', $ripple->embed());
    }

    public function testEmbedWithPlaylistUrlAndOptions(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="twitter:player" content="https://w.soundcloud.com/player/?url=https%3A%2F%2Fapi.soundcloud.com%2Fplaylists%2F456&amp;auto_play=false&amp;show_artwork=true&amp;visual=true&amp;origin=twitter">';
        $ripple->options(['response' => $response, 'embed' => ['SoundCloud' => 'show_comments=false']]);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame('https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/456&show_comments=false', $ripple->embed());
    }

    public function testEmbedWithTrackUrlAndArguments(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $this->assertSame('https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/123', $ripple->embed(self::URL_TRACK, '123'));
    }

    public function testEmbedWithPlaylistUrlAndArguments(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $this->assertSame('https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/456', $ripple->embed(self::URL_PLAYLIST, '456'));
    }

    public function testEmbedWithTrackUrlAndOptionsAndArguments(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['SoundCloud' => 'show_comments=false']]);
        $this->assertSame('https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/123&show_comments=false', $ripple->embed(self::URL_TRACK, '123'));
    }

    public function testEmbedWithPlaylistUrlAndOptionsAndArguments(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['SoundCloud' => 'show_comments=false']]);
        $this->assertSame('https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/456&show_comments=false', $ripple->embed(self::URL_PLAYLIST, '456'));
    }
}
