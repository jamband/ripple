<?php

/*
 * This file is part of the ripple library.
 *
 * (c) Tomoki Morita <tmsongbooks215@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace jamband\ripple\tests;

use jamband\ripple\Ripple;

class RippleTest extends \PHPUnit_Framework_TestCase
{
    const TRACK_BANDCAMP = 'https://example.bandcamp.com/track/title';
    const EMBED_BANDCAMP = 'https://bandcamp.com/EmbeddedPlayer/track=';

    const TRACK_SOUNDCLOUD = 'https://soundcloud.com/example/title';
    const EMBED_SOUNDCLOUD = 'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/';

    const TRACK_YOUTUBE = 'https://www.youtube.com/watch?v=AbCxYz012_';
    const EMBED_YOUTUBE = 'https://www.youtube.com/embed/';

    const TRACK_VIMEO = 'https://vimeo.com/1234567890';
    const EMBED_VIMEO = 'https://player.vimeo.com/video/';

    const TRACK_UNKNOWN_PROVIDER = 'https://www.example.com/';

    /**
     * @dataProvider constructProvider
     */
    public function testConstruct($url, $provider)
    {
        $ripple = new Ripple($url);
        $this->assertSame($url, $ripple->url);
        $this->assertSame($provider, $ripple->provider);
    }

    public function constructProvider()
    {
        return [
            [null, null],
            [self::TRACK_UNKNOWN_PROVIDER, null],
            [self::TRACK_BANDCAMP, 'Bandcamp'],
            [self::TRACK_SOUNDCLOUD, 'SoundCloud'],
            [self::TRACK_VIMEO, 'Vimeo'],
            [self::TRACK_YOUTUBE, 'YouTube'],
        ];
    }

    /**
     * @dataProvider setEmbedParamsWithoutSetParametersProvider
     */
    public function testSetEmbedParamsWithoutSetParameters($provider, $id, $embed)
    {
        $this->assertSame($embed, (new Ripple())->embed($provider, $id));
    }

    public function setEmbedParamsWithoutSetParametersProvider()
    {
        return [
            ['YouTube', 'AbCxYz012_', self::EMBED_YOUTUBE.'AbCxYz012_'],
            ['Vimeo', '1234567890', self::EMBED_VIMEO.'1234567890'],
            ['SoundCloud', '1234567890', self::EMBED_SOUNDCLOUD.'1234567890'],
            ['Bandcamp', '1234567890', self::EMBED_BANDCAMP.'1234567890/'],
        ];
    }

    /**
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
            [['YouTube' => '?autoplay=1'], 'YouTube', 'AbCxYz012_', self::EMBED_YOUTUBE.'AbCxYz012_?autoplay=1'],
            [['Vimeo' => '?autoplay=1'], 'Vimeo', '1234567890', self::EMBED_VIMEO.'1234567890?autoplay=1'],
            [['SoundCloud' => '?auto_play=true'], 'SoundCloud', '1234567890', self::EMBED_SOUNDCLOUD.'1234567890?auto_play=true'],
            [['Bandcamp' => 'size=large/'], 'Bandcamp', '1234567890', self::EMBED_BANDCAMP.'1234567890/size=large/'],
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
