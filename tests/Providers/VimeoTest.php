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

namespace Tests\Providers;

use Jamband\Ripple\Ripple;
use PHPUnit\Framework\TestCase;

class VimeoTest extends TestCase
{
    private const URL_TRACK = 'https://vimeo.com/123';

    public function testUrl(): void
    {
        // track
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK);
        $this->assertSame(self::URL_TRACK, $ripple->url());
    }

    public function testId(): void
    {
        // track
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('123', $ripple->id());
    }

    public function testTitle(): void
    {
        // track
        $ripple = new Ripple;
        $response = json_encode(['title' => 'Foo Title']);
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('Foo Title', $ripple->title());
    }

    public function testImage(): void
    {
        // track
        $ripple = new Ripple;
        $response = json_encode(['thumbnail_url' => 'https://image.example.com/123.jpg']);
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('https://image.example.com/123.jpg', $ripple->image());
    }

    public function testEmbed(): void
    {
        // track
        $ripple = new Ripple;
        $response = json_encode(['video_id' => 123]);
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('https://player.vimeo.com/video/123?rel=0', $ripple->embed());

        // track with options
        $ripple = new Ripple;
        $response = json_encode(['video_id' => 123]);
        $ripple->options(['response' => $response, 'embed' => ['Vimeo' => 'autoplay=1']]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('https://player.vimeo.com/video/123?rel=0&autoplay=1', $ripple->embed());

        // track with arguments
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $this->assertSame('https://player.vimeo.com/video/123?rel=0', $ripple->embed(self::URL_TRACK, '123'));

        // track with options and arguments
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['Vimeo' => 'autoplay=1']]);
        $this->assertSame('https://player.vimeo.com/video/123?rel=0&autoplay=1', $ripple->embed(self::URL_TRACK, '123'));
    }
}
