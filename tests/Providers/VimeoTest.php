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

    public function testUrlWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK);
        $this->assertSame(self::URL_TRACK, $ripple->url());
    }

    public function testIdWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('123', $ripple->id());
    }

    public function testTitleWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $response = json_encode(['title' => 'Foo Title']);
        assert(is_string($response));

        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('Foo Title', $ripple->title());
    }

    public function testImageWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $response = json_encode(['thumbnail_url' => 'https://image.example.com/123.jpg']);
        assert(is_string($response));

        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('https://image.example.com/123.jpg', $ripple->image());
    }

    public function testEmbedWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $response = json_encode(['video_id' => 123]);
        assert(is_string($response));

        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('https://player.vimeo.com/video/123?rel=0', $ripple->embed());
    }

    public function testEmbedWithTrackUrlAndOptions(): void
    {
        $ripple = new Ripple;
        $response = json_encode(['video_id' => 123]);
        assert(is_string($response));

        $ripple->options(['response' => $response, 'embed' => ['Vimeo' => 'autoplay=1']]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('https://player.vimeo.com/video/123?rel=0&autoplay=1', $ripple->embed());
    }

    public function testEmbedWithTrackUrlAndArguments(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $this->assertSame('https://player.vimeo.com/video/123?rel=0', $ripple->embed(self::URL_TRACK, '123'));
    }

    public function testEmbedWithTrackUrlAndOptionsAndArguments(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['Vimeo' => 'autoplay=1']]);
        $this->assertSame('https://player.vimeo.com/video/123?rel=0&autoplay=1', $ripple->embed(self::URL_TRACK, '123'));
    }
}
