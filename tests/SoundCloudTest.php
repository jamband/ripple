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

namespace tests;

use jamband\ripple\Ripple;
use PHPUnit\Framework\TestCase;

class SoundCloudTest extends TestCase
{
    /**
     * @var Ripple
     */
    private $track;

    /**
     * @var Ripple
     */
    private $playlist;

    public function setUp(): void
    {
        $this->track = new Ripple('https://soundcloud.com/account/title');
        $this->track->request([CURLOPT_URL => 'http://localhost:8080/soundcloud_track.html']);

        $this->playlist = new Ripple('https://soundcloud.com/account/sets/title');
        $this->playlist->request([CURLOPT_URL => 'http://localhost:8080/soundcloud_playlist.html']);
    }

    /**
     * @param null|string $url
     * @param bool $expected
     * @return void
     * @dataProvider validUrlPatternProvider
     */
    public function testValidUrlPattern(?string $url, bool $expected): void
    {
        $ripple = new Ripple($url);
        $this->assertSame($expected, $ripple->isValidUrl());
    }

    public function validUrlPatternProvider(): array
    {
        return [
            // track: failure
            ['https://soundcloud.com/example/title/path/to', false],
            ['https://soundcloud.com/example/title?query=value', false],
            ['https://soundcloud.com/example/title#fragment', false],

            // playlist: failure
            ['https://soundcloud.com/example/sets/title/path/to', false],
            ['https://soundcloud.com/example/sets/title?query=value', false],
            ['https://soundcloud.com/example/sets/title#fragment', false],

            // track: success
            ['https://soundcloud.com/example/title', true],
            ['https://www.soundcloud.com/example/title', true],
            ['http://soundcloud.com/example/title', true],
            ['http://www.soundcloud.com/example/title', true],

            // playlist: success
            ['https://soundcloud.com/example/sets/title', true],
            ['https://www.soundcloud.com/example/sets/title', true],
            ['http://soundcloud.com/example/sets/title', true],
            ['http://www.soundcloud.com/example/sets/title', true],
        ];
    }

    public function testId(): void
    {
        $this->assertSame('123', $this->track->id());
        $this->assertSame('456', $this->playlist->id());
    }

    public function testTitle(): void
    {
        $this->assertSame('SoundCloud Track Title', $this->track->title());
        $this->assertSame('SoundCloud Playlist Title', $this->playlist->title());
    }

    public function testImage(): void
    {
        $this->assertSame('soundcloud_track_thumbnail.jpg', $this->track->image());
        $this->assertSame('soundcloud_playlist_thumbnail.jpg', $this->playlist->image());
    }

    public function testEmbed(): void
    {
        $this->assertSame('https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/123', $this->track->embed());
        $this->assertSame('https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/456', $this->playlist->embed());
    }
}
