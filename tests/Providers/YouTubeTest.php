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

class YouTubeTest extends TestCase
{
    private const URL_TRACK_1 = 'https://www.youtube.com/watch?v=foo123';
    private const URL_TRACK_2 = 'https://youtu.be/foo123';
    private const URL_PLAYLIST = 'https://www.youtube.com/playlist?list=bar456';

    public function testUrlWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK_1);
        $this->assertSame(self::URL_TRACK_1, $ripple->url());
    }

    public function testUrlWithTrackShortUrl(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK_2);
        $this->assertSame(self::URL_TRACK_1, $ripple->url());
    }

    public function testUrlWithPlaylistUrl(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame(self::URL_PLAYLIST, $ripple->url());
    }

    public function testIdWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK_1);
        $this->assertSame('foo123', $ripple->id());
    }

    public function testIdWithTrackShortUrl(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK_2);
        $this->assertSame('foo123', $ripple->id());
    }

    public function testIdWithPlaylistUrl(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame('bar456', $ripple->id());
    }

    public function testTitleWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $response = json_encode(['title' => 'Foo Title']);
        assert(is_string($response));

        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK_1);
        $this->assertSame('Foo Title', $ripple->title());
    }

    public function testTitleWithPlaylistUrl(): void
    {
        $ripple = new Ripple;
        $response = json_encode(['title' => 'Bar Title']);
        assert(is_string($response));

        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame('Bar Title', $ripple->title());
    }

    public function testImageWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $response = json_encode(['thumbnail_url' => 'https://image.example.com/foo123.jpg']);
        assert(is_string($response));

        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK_1);
        $this->assertSame('https://image.example.com/foo123.jpg', $ripple->image());
    }

    public function testImageWithPlaylistUrl(): void
    {
        $ripple = new Ripple;
        $response = json_encode(['thumbnail_url' => 'https://image.example.com/bar456.jpg']);
        assert(is_string($response));

        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame('https://image.example.com/bar456.jpg', $ripple->image());
    }

    public function testEmbedWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK_1);
        $this->assertSame('https://www.youtube.com/embed/foo123?rel=0', $ripple->embed());
    }

    public function testEmbedWithPlaylistUrl(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame('https://www.youtube.com/embed/videoseries?list=bar456&rel=0', $ripple->embed());
    }

    public function testEmbedWithTrackUrlAndOptions(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['YouTube' => 'autoplay=1']]);
        $ripple->request(self::URL_TRACK_1);
        $this->assertSame('https://www.youtube.com/embed/foo123?rel=0&autoplay=1', $ripple->embed());
    }

    public function testEmbedWithPlaylistUrlAndOptions(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['YouTube' => 'autoplay=1']]);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame('https://www.youtube.com/embed/videoseries?list=bar456&rel=0&autoplay=1', $ripple->embed());
    }

    public function testEmbedWithTrackUrlAndArguments(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $this->assertSame('https://www.youtube.com/embed/foo123?rel=0', $ripple->embed(self::URL_TRACK_1, 'foo123'));
    }

    public function testEmbedWithPlaylistUrlAndArguments(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $this->assertSame('https://www.youtube.com/embed/videoseries?list=bar456&rel=0', $ripple->embed(self::URL_PLAYLIST, 'bar456'));
    }

    public function testEmbedWithTrackUrlAndOptionsAndArguments(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['YouTube' => 'autoplay=1']]);
        $this->assertSame('https://www.youtube.com/embed/foo123?rel=0&autoplay=1', $ripple->embed(self::URL_TRACK_1, 'foo123'));
    }

    public function testEmbedWithPlaylistUrlAndOptionsAndArguments(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['YouTube' => 'autoplay=1']]);
        $this->assertSame('https://www.youtube.com/embed/videoseries?list=bar456&rel=0&autoplay=1', $ripple->embed(self::URL_PLAYLIST, 'bar456'));
    }
}
