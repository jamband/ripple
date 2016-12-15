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
    public function setUp()
    {
        $this->ripple = new Ripple('https://example.bandcamp.com/track/title');
        $this->ripple->request([CURLOPT_URL => 'http://localhost:8080/bandcamp.html']);
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
        $this->assertSame('123', $this->ripple->id());
    }

    public function testTitle()
    {
        $this->assertSame('Bandcamp Title', $this->ripple->title());
    }

    public function testImage()
    {
        $this->assertSame('bandcamp_thumbnail.jpg', $this->ripple->image());
    }

    public function testEmbed()
    {
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/track=123/', $this->ripple->embed());
    }
}
