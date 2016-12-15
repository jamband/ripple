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

class RippleTest extends \PHPUnit_Framework_TestCase
{
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
            ['', null],
            ['https://www.example.com/', null],

            ['https://example.bandcamp.com/', 'Bandcamp'],
            ['https://music.botanicalhouse.net/track/title', 'Bandcamp'],
            ['https://souterraine.biz/track/title', 'Bandcamp'],

            ['https://soundcloud.com/example/title', 'SoundCloud'],
            ['https://vimeo.com/1234567890', 'Vimeo'],

            ['https://www.youtube.com/watch?v=AbCxYz012_-', 'YouTube'],
            ['https://youtu.be/AbCxYz012_-', 'YouTube'],
        ];
    }

    /**
     * Asserting by Ripple::id()
     * @param string $file
     * @param string $track
     * @param string $id
     * @dataProvider requestProvider
     */
    public function testRequest($file, $track, $id)
    {
        $ripple = new Ripple($track);
        $ripple->request([CURLOPT_URL => "http://localhost:8080/$file"]);
        $this->assertSame($id, $ripple->id());
    }

    public function requestProvider()
    {
        return [
            ['unknown.html', 'https://example.com/track/title', null],
            ['bandcamp.html', 'https://example.bandcamp.com/track/title', '123'],
            ['soundcloud.html', 'https://soundcloud.com/example/title', '123'],
            ['vimeo.json', 'https://vimeo.com/123', '123'],
            ['youtube.json', 'https://www.youtube.com/watch?v=123', '123'],
        ];
    }

    /**
     * @param string $file
     * @param string $url
     * @param string $embed
     * @dataProvider embedProvider
     */
    public function testEmbed($file, $url, $embed)
    {
        $ripple = new Ripple($url);
        $ripple->request([CURLOPT_URL => "http://localhost:8080/$file"]);
        $this->assertSame($embed, $ripple->embed());
    }

    public function embedProvider()
    {
        return [
            [
                'unknown.html',
                'https://example.com/',
                null
            ],
            [
                'bandcamp.html',
                'https://example.bandcamp.com/track/title',
                'https://bandcamp.com/EmbeddedPlayer/track=123/'
            ],
            [
                'soundcloud.html',
                'https://soundcloud.com/example/title',
                'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/123',
            ],
            [
                'vimeo.json',
                'https://vimeo.com/123',
                'https://player.vimeo.com/video/123',
            ],
            [
                'youtube.json',
                'https://www.youtube.com/watch?v=123',
                'https://www.youtube.com/embed/123',
            ],
        ];
    }

    /**
     * @param string $provider
     * @param string $id
     * @param string $embed
     * @dataProvider embedWithSetArgumentsProvider
     */
    public function testEmbedWithSetArguments($provider, $id, $embed)
    {
        $this->assertSame($embed, (new Ripple())->embed($provider, $id));
    }

    public function embedWithSetArgumentsProvider()
    {
        return [
            ['UnknownProvider', '1234567890', null],
            ['Bandcamp', '123', 'https://bandcamp.com/EmbeddedPlayer/track=123/'],
            ['SoundCloud', '123', 'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/123'],
            ['Vimeo', '123', 'https://player.vimeo.com/video/123'],
            ['YouTube', '123', 'https://www.youtube.com/embed/123'],
        ];
    }

    /**
     * @param array $params
     * @param string $provider
     * @param string $id
     * @param string $embed
     * @dataProvider setEmbedParamsProvider
     */
    public function testSetEmbedParams($params, $provider, $id, $embed)
    {
        $ripple = new Ripple();
        $ripple->setEmbedParams($params);
        $this->assertSame($embed, $ripple->embed($provider, $id));
    }

    public function setEmbedParamsProvider()
    {
        return [
            [
                ['UnknownProvider' => '?query=value'],
                'UnknownProvider',
                '123',
                null,
            ],
            [
                ['Bandcamp' => 'size=large/'],
                'Bandcamp',
                '123',
                'https://bandcamp.com/EmbeddedPlayer/track=123/size=large/',
            ],
            [
                ['SoundCloud' => '?auto_play=true'],
                'SoundCloud',
                '123',
                'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/123?auto_play=true',
            ],
            [
                ['Vimeo' => '?autoplay=1'],
                'Vimeo',
                '123',
                'https://player.vimeo.com/video/123?autoplay=1',
            ],
            [
                ['YouTube' => '?autoplay=1'],
                'YouTube',
                '123',
                'https://www.youtube.com/embed/123?autoplay=1',
            ],
            // Set a different provider
            [
                ['Bandcamp' => 'size=large/'],
                'YouTube',
                '123',
                'https://www.youtube.com/embed/123',
            ],
        ];
    }

    public function testProviders()
    {
        $this->assertSame([
            'Bandcamp',
            'SoundCloud',
            'Vimeo',
            'YouTube',
        ], Ripple::providers());
    }
}
