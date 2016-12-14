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

class SoundCloudTest extends \PHPUnit_Framework_TestCase
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
            ['https://soundcloud.com/example/title/path/to', false],
            ['https://soundcloud.com/example/title?query=value', false],
            ['https://soundcloud.com/example/title#fragment', false],

            // success
            ['https://soundcloud.com/example/title', true],
            ['https://www.soundcloud.com/example/title', true],
            ['http://soundcloud.com/example/title', true],
            ['http://www.soundcloud.com/example/title', true],
        ];
    }

    public function testId()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/SoundCloud.php'));

        $ripple = new Ripple('https://soundcloud.com/example/title');
        $ripple->request($client);

        $this->assertSame('1234567890', $ripple->id());
    }

    public function testTitle()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/SoundCloud.php'));

        $ripple = new Ripple('https://soundcloud.com/example/title');
        $ripple->request($client);

        $this->assertSame('SoundCloud Title', $ripple->title());
    }

    public function testImage()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/SoundCloud.php'));

        $ripple = new Ripple('https://soundcloud.com/example/title');
        $ripple->request($client);

        $this->assertSame('soundcloud_thumbnail.jpg', $ripple->image());
    }

    public function testEmbed()
    {
        $id = static::id();

        $ripple = new Ripple();
        $this->assertSame(
            "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/$id",
            $ripple->embed('SoundCloud', $id)
        );

        $ripple = new Ripple();
        $ripple->setEmbedParams(['SoundCloud' => '?auto_play=true']);
        $this->assertSame(
            "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/$id?auto_play=true",
            $ripple->embed('SoundCloud', $id)
        );
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
