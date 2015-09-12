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

class VimeoTest extends \PHPUnit_Framework_TestCase
{
    use ClientTrait;

    const URL_TRACK = 'https://vimeo.com/';
    const URL_EMBED = 'https://player.vimeo.com/video/';

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
            [self::URL_TRACK.'0123456789', false],
            [self::URL_TRACK.static::id().'?', false],
            [self::URL_TRACK.static::id().'/', false],
            [self::URL_TRACK.static::id().'&query=value', false],
            [self::URL_TRACK.static::id().'#fragment', false],
            // success
            [self::URL_TRACK.static::id(), true],
            ['https://www.vimeo.com/'.static::id(), true],
            ['http://vimeo.com/'.static::id(), true],
            ['http://www.vimeo.com/'.static::id(), true],
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
        $client->setClient($this->getGuzzle(static::content()));

        $ripple = new Ripple(self::URL_TRACK.static::id());
        $this->assertNull($ripple->title());

        $ripple->request($client);
        $this->assertInstanceOf('stdClass', $ripple->content);
        $this->assertSame('Vimeo Title', $ripple->title());
    }

    public function testImage()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(static::content()));

        $ripple = new Ripple(self::URL_TRACK.static::id());
        $this->assertNull($ripple->title());

        $ripple->request($client);
        $this->assertInstanceOf('stdClass', $ripple->content);
        $this->assertSame('vimeo_thumbnail.jpg', $ripple->image());
    }

    public function testEmbed()
    {
        $id = static::id();

        $ripple = new Ripple();
        $this->assertSame(self::URL_EMBED.$id, $ripple->embed('Vimeo', $id));

        $ripple = new Ripple();
        $ripple->setEmbedParams(['Vimeo' => '?autoplay=1']);
        $this->assertSame(self::URL_EMBED.$id.'?autoplay=1', $ripple->embed('Vimeo', $id));
    }

    /**
     * Generate Vimeo like ID. ([1-9][0-9]+)
     * @return string
     */
    private static function id()
    {
        return (string)mt_rand(10000000, 99999999);
    }

    private static function content()
    {
        return json_encode([
            'title' => 'Vimeo Title',
            'thumbnail_url' => 'vimeo_thumbnail.jpg',
        ]);
    }
}
