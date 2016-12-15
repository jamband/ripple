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

class SoundCloudTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->ripple = new Ripple('https://soundcloud.com/account/title');
        $this->ripple->request([CURLOPT_URL => 'http://localhost:8080/soundcloud.html']);
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
        $this->assertSame('123', $this->ripple->id());
    }

    public function testTitle()
    {
        $this->assertSame('SoundCloud Title', $this->ripple->title());
    }

    public function testImage()
    {
        $this->assertSame('soundcloud_thumbnail.jpg', $this->ripple->image());
    }

    public function testEmbed()
    {
        $this->assertSame('https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/123', $this->ripple->embed());
    }
}
