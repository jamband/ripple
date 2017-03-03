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

class YouTubeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Ripple
     */
    private $ripple;

    public function setUp()
    {
        $this->ripple = new Ripple('https://www.youtube.com/watch?v=123');
        $this->ripple->request([CURLOPT_URL => 'http://localhost:8080/youtube.json']);
    }

    /**
     * @param string $url
     * @param string $isValidUrl
     * @dataProvider validUrlPatternProvider
     */
    public function testValidUrlPattern($url, $isValidUrl)
    {
        $ripple = new Ripple($url);
        $this->assertSame($isValidUrl, $ripple->isValidUrl());
    }

    public function validUrlPatternProvider()
    {
        return [
            // failure
            ['https://www.youtube.com/watch?v='.static::id().'/', false],
            ['https://www.youtube.com/watch?v='.static::id().'?', false],
            ['https://www.youtube.com/watch?v='.static::id().'&query=value', false],
            ['https://www.youtube.com/watch?v='.static::id().'#fragment', false],
            ['https://youtu.be/'.static::id().'/', false],
            ['https://youtu.be/'.static::id().'?', false],
            ['https://youtu.be/'.static::id().'&query=value', false],
            ['https://youtu.be/'.static::id().'#fragment', false],

            // success
            ['https://www.youtube.com/watch?v='.static::id(), true],
            ['https://youtube.com/watch?v='.static::id(), true],
            ['http://www.youtube.com/watch?v='.static::id(), true],
            ['http://youtube.com/watch?v='.static::id(), true],
            ['https://youtu.be/'.static::id(), true],
            ['http://youtu.be/'.static::id(), true],
        ];
    }

    public function testId()
    {
        $this->assertSame('123', $this->ripple->id());
    }

    public function testTitle()
    {
        $this->assertSame('YouTube Title', $this->ripple->title());
    }

    public function testImage()
    {
        $this->assertSame('youtube_thumbnail.jpg', $this->ripple->image());
    }

    public function testEmbed()
    {
        $this->assertSame('https://www.youtube.com/embed/123', $this->ripple->embed());
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
