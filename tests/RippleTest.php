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

class RippleTest extends TestCase
{
    /**
     * @param null|string $url
     * @param null|string $provider
     * @return void
     * @dataProvider providerProvider
     */
    public function testProvider(?string $url, ?string $provider): void
    {
        $ripple = new Ripple($url);
        $this->assertSame($provider, $ripple->provider());
    }

    public function providerProvider(): array
    {
        return [
            [null, null],
            ['', null],
            ['example', null],
            ['https://www.example.com/', null],

            ['https://example.bandcamp.com/', 'Bandcamp'],
            ['http://music.botanicalhouse.net/track/title', 'Bandcamp'],
            ['http://souterraine.biz/track/title', 'Bandcamp'],

            ['https://soundcloud.com/example/title', 'SoundCloud'],
            ['https://soundcloud.com/example/sets/title', 'SoundCloud'],

            ['https://vimeo.com/123', 'Vimeo'],

            ['https://www.youtube.com/watch?v=123', 'YouTube'],
            ['https://youtu.be/123', 'YouTube'],
            ['https://www.youtube.com/playlist?list=456', 'YouTube'],
        ];
    }

    /**
     * Asserting by Ripple::id()
     *
     * @param string $file
     * @param null|string $track
     * @param null|string $id
     * @return void
     * @dataProvider requestProvider
     */
    public function testRequest(string $file, ?string $track, ?string $id): void
    {
        $ripple = new Ripple($track);
        $ripple->request([CURLOPT_URL => "http://localhost:8080/$file"]);
        $this->assertSame($id, $ripple->id());
    }

    public function requestProvider(): array
    {
        return [
            ['unknown.html', 'https://example.com/track/title', null],

            ['bandcamp_not_found.html', 'https://example.bandcamp.com/track/title', null],
            ['bandcamp_track.html', 'https://example.bandcamp.com/track/title', '123'],
            ['bandcamp_album.html', 'https://example.bandcamp.com/album/title', '456'],

            ['soundcloud_not_found.html', 'https://soundcloud.com/example/title', null],
            ['soundcloud_track.html', 'https://soundcloud.com/example/title', '123'],
            ['soundcloud_playlist.html', 'https://soundcloud.com/example/sets/title', '456'],

            ['vimeo_not_found.json', 'https://vimeo.com/123', null],
            ['vimeo_video.json', 'https://vimeo.com/123', '123'],

            ['youtube_not_found.json', 'https://www.youtube.com/watch?v=123', null],
            ['youtube_video.json', 'https://www.youtube.com/watch?v=123', '123'],
            ['youtube_playlist.json', 'https://www.youtube.com/playlist?list=456', '456'],
        ];
    }

    /**
     * @param string $file
     * @param string $url
     * @param null|string $embed
     * @return void
     * @dataProvider embedProvider
     */
    public function testEmbed(string $file, string $url, ?string $embed): void
    {
        $ripple = new Ripple($url);
        $ripple->request([CURLOPT_URL => "http://localhost:8080/$file"]);
        $this->assertSame($embed, $ripple->embed());
    }

    public function embedProvider(): array
    {
        return [
            [
                'unknown.html',
                'https://example.com/',
                null
            ],
            [
                'bandcamp_track.html',
                'https://example.bandcamp.com/track/title',
                'https://bandcamp.com/EmbeddedPlayer/track=123/'
            ],
            [
                'bandcamp_album.html',
                'https://example.bandcamp.com/album/title',
                'https://bandcamp.com/EmbeddedPlayer/album=456/'
            ],
            [
                'soundcloud_track.html',
                'https://soundcloud.com/example/title',
                'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/123',
            ],
            [
                'soundcloud_playlist.html',
                'https://soundcloud.com/example/sets/title',
                'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/456',
            ],
            [
                'vimeo_video.json',
                'https://vimeo.com/123',
                'https://player.vimeo.com/video/123?rel=0',
            ],
            [
                'youtube_video.json',
                'https://www.youtube.com/watch?v=123',
                'https://www.youtube.com/embed/123?rel=0',
            ],
            [
                'youtube_playlist.json',
                'https://www.youtube.com/playlist?list=456',
                'https://www.youtube.com/embed/videoseries?list=456&rel=0',
            ],
        ];
    }

    /**
     * @param string $url
     * @param string $id
     * @param null|string $embed
     * @return void
     * @dataProvider embedWithSetArgumentsProvider
     */
    public function testEmbedWithSetArguments(string $url, string $id, ?string $embed): void
    {
        $this->assertSame($embed, (new Ripple())->embed($url, $id));
    }

    public function embedWithSetArgumentsProvider(): array
    {
        return [
            [
                'https://example.com/track/title',
                '1234567890',
                null,
            ],
            [
                'https://example.bandcamp.com/track/title',
                '123',
                'https://bandcamp.com/EmbeddedPlayer/track=123/',
            ],
            [
                'https://example.bandcamp.com/album/title',
                '456',
                'https://bandcamp.com/EmbeddedPlayer/album=456/',
            ],
            [
                'https://soundcloud.com/account/title',
                '123',
                'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/123',
            ],
            [
                'https://soundcloud.com/account/sets/title',
                '456',
                'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/456',
            ],
            [
                'https://vimeo.com/123',
                '123',
                'https://player.vimeo.com/video/123?rel=0',
            ],
            [
                'https://www.youtube.com/watch?v=123',
                '123',
                'https://www.youtube.com/embed/123?rel=0',
            ],
            [
                'https://www.youtube.com/playlist?list=456',
                '456',
                'https://www.youtube.com/embed/videoseries?list=456&rel=0',
            ],
        ];
    }

    /**
     * @param array $params
     * @param string $url
     * @param string $id
     * @param null|string $embed
     * @return void
     * @dataProvider setEmbedParamsProvider
     */
    public function testSetEmbedParams(array $params, string $url, string $id, ?string $embed): void
    {
        $ripple = new Ripple();
        $ripple->setEmbedParams($params);
        $this->assertSame($embed, $ripple->embed($url, $id));
    }

    public function setEmbedParamsProvider(): array
    {
        return [
            [
                ['UnknownProvider' => '?query=value'],
                'https://example.com/track/title',
                '123',
                null,
            ],
            [
                ['Bandcamp' => 'size=large/'],
                'https://example.bandcamp.com/track/title',
                '123',
                'https://bandcamp.com/EmbeddedPlayer/track=123/size=large/',
            ],
            [
                ['Bandcamp' => 'size=large/'],
                'https://example.bandcamp.com/album/title',
                '456',
                'https://bandcamp.com/EmbeddedPlayer/album=456/size=large/',
            ],
            [
                ['SoundCloud' => '&auto_play=true'],
                'https://soundcloud.com/track/title',
                '123',
                'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/123&auto_play=true',
            ],
            [
                ['SoundCloud' => '&auto_play=true'],
                'https://soundcloud.com/account/sets/title',
                '456',
                'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/456&auto_play=true',
            ],
            [
                ['Vimeo' => '&autoplay=1'],
                'https://vimeo.com/123',
                '123',
                'https://player.vimeo.com/video/123?rel=0&autoplay=1',
            ],
            [
                ['YouTube' => '&autoplay=1'],
                'https://www.youtube.com/watch?v=123',
                '123',
                'https://www.youtube.com/embed/123?rel=0&autoplay=1',
            ],
            [
                ['YouTube' => '&autoplay=1'],
                'https://www.youtube.com/playlist?list=456',
                '456',
                'https://www.youtube.com/embed/videoseries?list=456&rel=0&autoplay=1',
            ],
        ];
    }

    public function testProviders(): void
    {
        $this->assertSame([
            'Bandcamp',
            'SoundCloud',
            'Vimeo',
            'YouTube',
        ], Ripple::providers());
    }
}
