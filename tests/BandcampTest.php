<?php

/*
 * This file is part of the ripple library.
 *
 * (c) Tomoki Morita <tmsongbooks215@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests;

use jamband\ripple\Ripple;

class BandcampTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Ripple
     */
    private $track;

    /**
     * @var Ripple
     */
    private $album;

    public function setUp()
    {
        $this->track = new Ripple('https://example.bandcamp.com/track/title');
        $this->track->request([CURLOPT_URL => 'http://localhost:8080/bandcamp_track.html']);

        $this->album = new Ripple('https://example.bandcamp.com/album/title');
        $this->album->request([CURLOPT_URL => 'http://localhost:8080/bandcamp_album.html']);
    }

    /**
     * @param string $url
     * @param string $provider
     * @dataProvider providerProvider
     */
    public function testProvider($url, $provider)
    {
        $ripple = new Ripple($url);
        $this->assertSame($provider, $ripple->provider());
    }

    public function providerProvider()
    {
        return [
            ['https://bandcamp.com/track/title', 'Bandcamp'],
            ['http://music.botanicalhouse.net/', 'Bandcamp'],
            ['http://tunes.mamabirdrecordingco.com/', 'Bandcamp'],
            ['http://souterraine.biz/', 'Bandcamp'],
            ['http://sunnysidezone.com/', 'Bandcamp'],
        ];
    }

    /**
     * @param string $url
     * @param string $expected
     * @dataProvider validUrlPatternProvider
     */
    public function testValidUrlPattern($url, $expected)
    {
        $ripple = new Ripple($url);
        $this->assertSame($expected, $ripple->isValidUrl());
    }

    public function validUrlPatternProvider()
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

    public function testId()
    {
        $this->assertSame('123', $this->track->id());
        $this->assertSame('456', $this->album->id());
    }

    public function testTitle()
    {
        $this->assertSame('Bandcamp Track Title', $this->track->title());
        $this->assertSame('Bandcamp Album Title', $this->album->title());
    }

    public function testImage()
    {
        $this->assertSame('https://img.example.com/bandcamp_track_thumbnail.jpg', $this->track->image());
        $this->assertSame('https://img.example.com/bandcamp_album_thumbnail.jpg', $this->album->image());
    }

    public function testEmbed()
    {
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/track=123/', $this->track->embed());
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/album=456/', $this->album->embed());
    }
}
