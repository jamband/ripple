<?php

/*
 * This file is part of the ripple library.
 *
 * (c) Tomoki Morita <tmsongbooks215@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace tests;

use jamband\ripple\Ripple;
use PHPUnit\Framework\TestCase;

class VimeoTest extends TestCase
{
    /**
     * @var Ripple
     */
    private $video;

    /**
     * @var Ripple
     */
    private $playlist;

    public function setUp(): void
    {
        $this->video = new Ripple('https://vimeo.com/123');
        $this->video->request([CURLOPT_URL => 'http://localhost:8080/vimeo_video.json']);

        $this->playlist = new Ripple('https://vimeo.com/album/456');
        $this->playlist->request([CURLOPT_URL => 'http://localhost:8080/vimeo_playlist.json']);
    }

    /**
     * @param null|string $url
     * @param bool $isValidUrl
     * @return void
     * @dataProvider validUrlPatternProvider
     */
    public function testValidUrlPattern(?string $url, bool $isValidUrl): void
    {
        $ripple = new Ripple($url);
        $this->assertSame($isValidUrl, $ripple->isValidUrl());
    }

    public function validUrlPatternProvider(): array
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

    public function testId(): void
    {
        $this->assertSame('123', $this->video->id());
        $this->assertSame('456', $this->playlist->id());
    }

    public function testTitle(): void
    {
        $this->assertSame('Vimeo Video Title', $this->video->title());
        $this->assertSame('Vimeo Playlist Title', $this->playlist->title());
    }

    public function testImage(): void
    {
        $this->assertSame('vimeo_video_thumbnail.jpg', $this->video->image());
        $this->assertSame('vimeo_playlist_thumbnail.jpg', $this->playlist->image());
    }

    public function testEmbed(): void
    {
        $this->assertSame('https://player.vimeo.com/video/123?rel=0', $this->video->embed());
        $this->assertSame('https://player.vimeo.com/video/album/456?rel=0', $this->playlist->embed());
    }
}
