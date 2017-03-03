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

class VimeoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Ripple
     */
    private $ripple;

    public function setUp()
    {
        $this->ripple = new Ripple('https://vimeo.com/123');
        $this->ripple->request([CURLOPT_URL => 'http://localhost:8080/vimeo.json']);
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
            ['https://vimeo.com/0123', false],
            ['https://vimeo.com/123?', false],
            ['https://vimeo.com/123/', false],
            ['https://vimeo.com/123&query=value', false],
            ['https://vimeo.com/123#fragment', false],

            // success
            ['https://vimeo.com/123', true],
            ['https://www.vimeo.com/123', true],
            ['http://vimeo.com/123', true],
            ['http://www.vimeo.com/123', true],
        ];
    }

    public function testId()
    {
        $this->assertSame('123', $this->ripple->id());
    }

    public function testTitle()
    {
        $this->assertSame('Vimeo Title', $this->ripple->title());
    }

    public function testImage()
    {
        $this->assertSame('vimeo_thumbnail.jpg', $this->ripple->image());
    }

    public function testEmbed()
    {
        $this->assertSame('https://player.vimeo.com/video/123', $this->ripple->embed());
    }
}
