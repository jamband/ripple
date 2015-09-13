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

class YouTubeTest extends \PHPUnit_Framework_TestCase
{
    use ClientTrait;

    const URL_TRACK = 'https://www.youtube.com/watch?v=';
    const URL_EMBED = 'https://www.youtube.com/embed/';

    /**
     * @dataProvider isValidUrlProvider
     */
    public function testIsValidUrl($url, $isValidUrl)
    {
        $ripple = new Ripple($url);
        $this->assertSame($isValidUrl, $ripple->isValidUrl());
    }

    public function isValidUrlProvider()
    {
        return [
            // failure
            [self::URL_TRACK.static::id().'/', false],
            [self::URL_TRACK.static::id().'?', false],
            [self::URL_TRACK.static::id().'&query=value', false],
            [self::URL_TRACK.static::id().'#fragment', false],
            // success
            [self::URL_TRACK.static::id(), true],
            ['https://youtube.com/watch?v='.static::id(), true],
            ['http://www.youtube.com/watch?v='.static::id(), true],
            ['http://youtube.com/watch?v='.static::id(), true],
        ];
    }

    public function testId()
    {
        $ripple = new Ripple();
        $this->assertNull($ripple->id());

        $id = static::id();
        $ripple = new Ripple(self::URL_TRACK.$id);
        $this->assertSame($id, $ripple->id());
    }

    public function testTitle()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/YouTube.php'));

        $ripple = new Ripple(self::URL_TRACK.static::id());
        $this->assertNull($ripple->title());

        $ripple->request($client);
        $this->assertSame('YouTube Title', $ripple->title());
    }

    public function testImage()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/YouTube.php'));

        $ripple = new Ripple(self::URL_TRACK.static::id());
        $this->assertNull($ripple->title());

        $ripple->request($client);
        $this->assertSame('youtube_thumbnail.jpg', $ripple->image());
    }

    public function testEmbed()
    {
        $id = static::id();

        $ripple = new Ripple();
        $this->assertSame(self::URL_EMBED.$id, $ripple->embed('YouTube', $id));

        $ripple = new Ripple();
        $ripple->setEmbedParams(['YouTube' => '?autoplay=1']);
        $this->assertSame(self::URL_EMBED.$id.'?autoplay=1', $ripple->embed('YouTube', $id));
    }

    /**
     * Generate YouTube like ID. (e.g. pButZ3Littk, P5xR6zWO_no, _-Hv-oThSwc)
     * @return string
     */
    private static function id()
    {
        return rtrim(strtr(base64_encode(openssl_random_pseudo_bytes(8)), '+/', '-_'), '=');
    }
}
