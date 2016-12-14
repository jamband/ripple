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
use Goutte\Client;

class BandcampTest extends \PHPUnit_Framework_TestCase
{
    use ClientTrait;

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
            // failure
            ['https://example.bandcamp.com/track/title/path/to', false],
            ['https://example.bandcamp.com/track/title?query=value', false],
            ['https://example.bandcamp.com/track/title#fragment', false],
            ['https://music.botanicalhouse.net/track/title/path/to', false],
            ['https://music.botanicalhouse.net/track/title?query=value', false],
            ['https://music.botanicalhouse.net/track/title#fragment', false],
            ['https://souterraine.biz/track/title/path/to', false],
            ['https://souterraine.biz/track/title?query=value', false],
            ['https://souterraine.biz/track/title#fragment', false],

            // success
            ['https://example.bandcamp.com/track/title', true],
            ['https://123example.bandcamp.com/track/title', true],
            ['https://music.botanicalhouse.net/track/title', true],
            ['https://souterraine.biz/track/title', true],
        ];
    }

    public function testId()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/Bandcamp.php'));

        $ripple = new Ripple('https://example.bandcamp.com/track/title');
        $ripple->request($client);

        $this->assertSame('1234567890', $ripple->id());
    }

    public function testTitle()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/Bandcamp.php'));

        $ripple = new Ripple('https://example.bandcamp.com/track/title');
        $ripple->request($client);

        $this->assertSame('Bandcamp Title', $ripple->title());
    }

    public function testImage()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/Bandcamp.php'));

        $ripple = new Ripple('https://example.bandcamp.com/track/title');
        $ripple->request($client);

        $this->assertSame('bandcamp-thumbnail.jpg', $ripple->image());
    }

    public function testEmbed()
    {
        $id = static::id();

        $ripple = new Ripple();
        $this->assertSame("https://bandcamp.com/EmbeddedPlayer/track=$id/", $ripple->embed('Bandcamp', $id));

        $ripple = new Ripple();
        $ripple->setEmbedParams(['Bandcamp' => 'size=large/']);
        $this->assertSame("https://bandcamp.com/EmbeddedPlayer/track=$id/size=large/", $ripple->embed('Bandcamp', $id));
    }

    /**
     * Generate Bandcamp like ID. ([1-9][0-9]+)
     * @return string
     */
    private static function id()
    {
        return (string)mt_rand(10000000, 99999999);
    }
}
