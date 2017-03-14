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
    private $video;

    /**
     * @var Ripple
     */
    private $playlist;

    public function setUp()
    {
        $this->video = new Ripple('https://vimeo.com/123');
        $this->video->request([CURLOPT_URL => 'http://localhost:8080/vimeo_video.json']);

        $this->playlist = new Ripple('https://vimeo.com/album/456');
        $this->playlist->request([CURLOPT_URL => 'http://localhost:8080/vimeo_playlist.json']);
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
            // video: failure
            ['https://vimeo.com/0123', false],
            ['https://vimeo.com/123?', false],
            ['https://vimeo.com/123/', false],
            ['https://vimeo.com/123&query=value', false],
            ['https://vimeo.com/123#fragment', false],

            // video: success
            ['https://vimeo.com/123', true],
            ['https://www.vimeo.com/123', true],
            ['http://vimeo.com/123', true],
            ['http://www.vimeo.com/123', true],
        ];
    }

    public function testId()
    {
        $this->assertSame('123', $this->video->id());
        $this->assertSame('456', $this->playlist->id());
    }

    public function testTitle()
    {
        $this->assertSame('Vimeo Video Title', $this->video->title());
        $this->assertSame('Vimeo Playlist Title', $this->playlist->title());
    }

    public function testImage()
    {
        $this->assertSame('vimeo_video_thumbnail.jpg', $this->video->image());
        $this->assertSame('vimeo_playlist_thumbnail.jpg', $this->playlist->image());
    }

    public function testEmbed()
    {
        $this->assertSame('https://player.vimeo.com/video/123', $this->video->embed());
        $this->assertSame('https://player.vimeo.com/video/album/456', $this->playlist->embed());
    }
}
