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

    public function testUrl(): void
    {
        // track
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK_1);
        $this->assertSame(self::URL_TRACK_1, $ripple->url());

        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK_2);
        $this->assertSame(self::URL_TRACK_1, $ripple->url());

        // playlist
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame(self::URL_PLAYLIST, $ripple->url());
    }

    public function testId(): void
    {
        // track
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK_1);
        $this->assertSame('foo123', $ripple->id());

        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK_2);
        $this->assertSame('foo123', $ripple->id());

        // playlist
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame('bar456', $ripple->id());
    }

    public function testTitle(): void
    {
        // track
        $ripple = new Ripple;
        $response = json_encode(['title' => 'Foo Title']);
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK_1);
        $this->assertSame('Foo Title', $ripple->title());

        // playlist
        $ripple = new Ripple;
        $response = json_encode(['title' => 'Bar Title']);
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame('Bar Title', $ripple->title());
    }

    public function testImage(): void
    {
        // track
        $ripple = new Ripple;
        $response = json_encode(['thumbnail_url' => 'https://image.example.com/foo123.jpg']);
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK_1);
        $this->assertSame('https://image.example.com/foo123.jpg', $ripple->image());

        // playlist
        $ripple = new Ripple;
        $response = json_encode(['thumbnail_url' => 'https://image.example.com/bar456.jpg']);
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame('https://image.example.com/bar456.jpg', $ripple->image());
    }

    public function testEmbed(): void
    {
        // track
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK_1);
        $this->assertSame('https://www.youtube.com/embed/foo123?rel=0', $ripple->embed());

        // playlist
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame('https://www.youtube.com/embed/videoseries?list=bar456&rel=0', $ripple->embed());

        // track with options
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['YouTube' => 'autoplay=1']]);
        $ripple->request(self::URL_TRACK_1);
        $this->assertSame('https://www.youtube.com/embed/foo123?rel=0&autoplay=1', $ripple->embed());

        // playlist with options
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['YouTube' => 'autoplay=1']]);
        $ripple->request(self::URL_PLAYLIST);
        $this->assertSame('https://www.youtube.com/embed/videoseries?list=bar456&rel=0&autoplay=1', $ripple->embed());

        // track with arguments
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $this->assertSame('https://www.youtube.com/embed/foo123?rel=0', $ripple->embed(self::URL_TRACK_1, 'foo123'));

        // playlist with arguments
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $this->assertSame('https://www.youtube.com/embed/videoseries?list=bar456&rel=0', $ripple->embed(self::URL_PLAYLIST, 'bar456'));

        // track with options and arguments
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['YouTube' => 'autoplay=1']]);
        $this->assertSame('https://www.youtube.com/embed/foo123?rel=0&autoplay=1', $ripple->embed(self::URL_TRACK_1, 'foo123'));

        // playlist with options and arguments
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['YouTube' => 'autoplay=1']]);
        $this->assertSame('https://www.youtube.com/embed/videoseries?list=bar456&rel=0&autoplay=1', $ripple->embed(self::URL_PLAYLIST, 'bar456'));
    }
}
