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
use Goutte\Client;

class SoundCloudTest extends \PHPUnit_Framework_TestCase
{
    use ClientTrait;

    const URL_TRACK = 'https://soundcloud.com/example/title';
    const URL_EMBED = 'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/';

    /**
     * @dataProvider isValidUrlProvider
     */
    public function testIsValidUrl($url, $expected)
    {
        $ripple = new Ripple($url);
        $this->assertSame($expected, $ripple->isValidUrl());
    }

    public function isValidUrlProvider()
    {
        return [
            // failure
            [self::URL_TRACK.'/path/to', false],
            [self::URL_TRACK.'?query=value', false],
            [self::URL_TRACK.'#fragment', false],
            // success
            [self::URL_TRACK, true],
            ['https://www.soundcloud.com/example/title', true],
            ['http://soundcloud.com/example/title', true],
            ['http://www.soundcloud.com/example/title', true],
        ];
    }

    public function testId()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/SoundCloud.php'));

        $ripple = new Ripple(self::URL_TRACK);
        $ripple->request($client);

        $this->assertSame('1234567890', $ripple->id());
    }

    public function testTitle()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/SoundCloud.php'));

        $ripple = new Ripple(self::URL_TRACK);
        $ripple->request($client);

        $this->assertSame('SoundCloud Title', $ripple->title());
    }

    public function testImage()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/SoundCloud.php'));

        $ripple = new Ripple(self::URL_TRACK);
        $ripple->request($client);

        $this->assertSame('soundcloud_thumbnail.jpg', $ripple->image());
    }

    public function testEmbed()
    {
        $id = static::id();

        $ripple = new Ripple();
        $this->assertSame(self::URL_EMBED.$id, $ripple->embed('SoundCloud', $id));

        $ripple = new Ripple();
        $ripple->setEmbedParams(['SoundCloud' => '?auto_play=true']);
        $this->assertSame(self::URL_EMBED.$id.'?auto_play=true', $ripple->embed('SoundCloud', $id));
    }

    /**
     * Generate SoundCloud like ID. ([1-9][0-9]+)
     * @return string
     */
    private static function id()
    {
        return (string)mt_rand(10000000, 99999999);
    }
}
