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

use Goutte\Client;
use jamband\ripple\Ripple;

class RippleTest extends \PHPUnit_Framework_TestCase
{
    use ClientTrait;

    const TRACK_BANDCAMP = 'https://example.bandcamp.com/track/title';
    const EMBED_BANDCAMP = 'https://bandcamp.com/EmbeddedPlayer/track=';

    const TRACK_SOUNDCLOUD = 'https://soundcloud.com/example/title';
    const EMBED_SOUNDCLOUD = 'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/';

    const TRACK_YOUTUBE = 'https://www.youtube.com/watch?v=AbCxYz012_-';
    const TRACK_YOUTUBE2 = 'https://youtu.be/AbCxYz012_-';
    const EMBED_YOUTUBE = 'https://www.youtube.com/embed/';

    const TRACK_VIMEO = 'https://vimeo.com/1234567890';
    const EMBED_VIMEO = 'https://player.vimeo.com/video/';

    const TRACK_UNKNOWN_PROVIDER = 'https://www.example.com/';

    /**
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
            [self::TRACK_UNKNOWN_PROVIDER, null],
            [self::TRACK_BANDCAMP, 'Bandcamp'],
            [self::TRACK_SOUNDCLOUD, 'SoundCloud'],
            [self::TRACK_VIMEO, 'Vimeo'],
            [self::TRACK_YOUTUBE, 'YouTube'],
            [self::TRACK_YOUTUBE2, 'YouTube'],
        ];
    }

    /**
     * Asserting by Ripple::id()
     * @dataProvider requestProvider
     */
    public function testRequest($file, $track, $id)
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__."/response/$file.php"));

        $ripple = new Ripple($track);
        $ripple->request($client);
        $this->assertSame($id, $ripple->id());
    }

    public function requestProvider()
    {
        return [
            ['UnknownProvider', self::TRACK_SOUNDCLOUD, null],
            ['Bandcamp', self::TRACK_BANDCAMP, '1234567890'],
            ['SoundCloud', self::TRACK_SOUNDCLOUD, '1234567890'],
            ['Vimeo', self::TRACK_VIMEO, '1234567890'],
            ['YouTube', self::TRACK_YOUTUBE, 'AbCxYz012_-'],
        ];
    }

    /**
     * @dataProvider embedProvider
     */
    public function testEmbed($file, $url, $embed)
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__."/response/$file.php"));

        $ripple = new Ripple($url);
        $ripple->request($client);

        $this->assertSame($embed, $ripple->embed());
    }

    public function embedProvider()
    {
        return [
            ['UnknownProvider', self::TRACK_UNKNOWN_PROVIDER, null],
            ['Bandcamp', self::TRACK_BANDCAMP, self::EMBED_BANDCAMP.'1234567890/'],
            ['SoundCloud', self::TRACK_SOUNDCLOUD, self::EMBED_SOUNDCLOUD.'1234567890'],
            ['Vimeo', self::TRACK_VIMEO, self::EMBED_VIMEO.'1234567890'],
            ['YouTube', self::TRACK_YOUTUBE, self::EMBED_YOUTUBE.'AbCxYz012_-'],
        ];
    }

    /**
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
            ['Bandcamp', '1234567890', self::EMBED_BANDCAMP.'1234567890/'],
            ['SoundCloud', '1234567890', self::EMBED_SOUNDCLOUD.'1234567890'],
            ['Vimeo', '1234567890', self::EMBED_VIMEO.'1234567890'],
            ['YouTube', 'AbCxYz012_', self::EMBED_YOUTUBE.'AbCxYz012_'],
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
            [['UnknownProvider' => '?query=value'], 'UnknownProvider', 'AbCxYz012_-', null],
            [['Bandcamp' => 'size=large/'], 'Bandcamp', '1234567890', self::EMBED_BANDCAMP.'1234567890/size=large/'],
            [['SoundCloud' => '?auto_play=true'], 'SoundCloud', '1234567890', self::EMBED_SOUNDCLOUD.'1234567890?auto_play=true'],
            [['Vimeo' => '?autoplay=1'], 'Vimeo', '1234567890', self::EMBED_VIMEO.'1234567890?autoplay=1'],
            [['YouTube' => '?autoplay=1'], 'YouTube', 'AbCxYz012_-', self::EMBED_YOUTUBE.'AbCxYz012_-?autoplay=1'],
            // Set a different provider
            [['Bandcamp' => 'size=large/'], 'YouTube', 'AbCxYz012_-', self::EMBED_YOUTUBE.'AbCxYz012_-'],
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
