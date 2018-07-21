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

class BandcampTest extends TestCase
{
    /**
     * @var Ripple
     */
    private $track;

    /**
     * @var Ripple
     */
    private $album;

    public function setUp(): void
    {
        $this->track = new Ripple('https://example.bandcamp.com/track/title');
        $this->track->request([CURLOPT_URL => 'http://localhost:8080/bandcamp_track.html']);

        $this->album = new Ripple('https://example.bandcamp.com/album/title');
        $this->album->request([CURLOPT_URL => 'http://localhost:8080/bandcamp_album.html']);
    }

    /**
     * @param string $url
     * @param string $provider
     * @return void
     * @dataProvider providerProvider
     */
    public function testProvider(string $url, string $provider): void
    {
        $ripple = new Ripple($url);
        $this->assertSame($provider, $ripple->provider());
    }

    public function providerProvider(): array
    {
        return [
            ['https://bandcamp.com/track/title', 'Bandcamp'],
            ['http://downloads.maybemars.org/', 'Bandcamp'],
            ['http://music.botanicalhouse.net/', 'Bandcamp'],
            ['http://tunes.mamabirdrecordingco.com/', 'Bandcamp'],
            ['http://souterraine.biz/', 'Bandcamp'],
            ['http://sunnysidezone.com/', 'Bandcamp'],
            ['http://shop.fikarecordings.com/', 'Bandcamp'],
        ];
    }

    /**
     * @param string $url
     * @param bool $expected
     * @return void
     * @dataProvider validUrlPatternProvider
     */
    public function testValidUrlPattern(string $url, bool $expected): void
    {
        $ripple = new Ripple($url);
        $this->assertSame($expected, $ripple->isValidUrl());
    }

    public function validUrlPatternProvider(): array
    {
        return [
            // track: failure
            ['https://example.bandcamp.com/track/title/path/to', false],
            ['https://example.bandcamp.com/track/title?query=value', false],
            ['https://example.bandcamp.com/track/title#fragment', false],
            ['http://music.botanicalhouse.net/track/title/path/to', false],
            ['http://music.botanicalhouse.net/track/title?query=value', false],
            ['http://music.botanicalhouse.net/track/title#fragment', false],
            ['http://souterraine.biz/track/title/path/to', false],
            ['http://souterraine.biz/track/title?query=value', false],
            ['http://souterraine.biz/track/title#fragment', false],

            // album: failure
            ['https://example.bandcamp.com/album/title/path/to', false],
            ['https://example.bandcamp.com/album/title?query=value', false],
            ['https://example.bandcamp.com/album/title#fragment', false],
            ['http://music.botanicalhouse.net/album/title/path/to', false],
            ['http://music.botanicalhouse.net/album/title?query=value', false],
            ['http://music.botanicalhouse.net/album/title#fragment', false],
            ['http://souterraine.biz/album/title/path/to', false],
            ['http://souterraine.biz/album/title?query=value', false],
            ['http://souterraine.biz/album/title#fragment', false],

            // track: success
            ['https://example.bandcamp.com/track/title', true],
            ['https://123example.bandcamp.com/track/title', true],
            ['http://music.botanicalhouse.net/track/title', true],
            ['http://souterraine.biz/track/title', true],

            // album: success
            ['https://example.bandcamp.com/album/title', true],
            ['https://123example.bandcamp.com/album/title', true],
            ['http://music.botanicalhouse.net/album/title', true],
            ['http://souterraine.biz/album/title', true],
        ];
    }

    public function testId(): void
    {
        $this->assertSame('123', $this->track->id());
        $this->assertSame('456', $this->album->id());
    }

    public function testTitle(): void
    {
        $this->assertSame('Bandcamp Track Title', $this->track->title());
        $this->assertSame('Bandcamp Album Title', $this->album->title());
    }

    public function testImage(): void
    {
        $this->assertSame('https://img.example.com/bandcamp_track_thumbnail.jpg', $this->track->image());
        $this->assertSame('https://img.example.com/bandcamp_album_thumbnail.jpg', $this->album->image());
    }

    public function testEmbed(): void
    {
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/track=123/', $this->track->embed());
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/album=456/', $this->album->embed());
    }
}
