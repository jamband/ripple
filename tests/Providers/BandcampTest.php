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

class BandcampTest extends TestCase
{
    private const URL_TRACK = 'https://foo.bandcamp.com/track/title';
    private const URL_ALBUM = 'https://foo.bandcamp.com/album/title';

    public function testUrl(): void
    {
        // track
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK);
        $this->assertSame(self::URL_TRACK, $ripple->url());

        // album
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_ALBUM);
        $this->assertSame(self::URL_ALBUM, $ripple->url());
    }

    public function testId(): void
    {
        // track
        $ripple = new Ripple;
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/track=123/">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('123', $ripple->id());

        // album
        $ripple = new Ripple;
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/album=456/">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_ALBUM);
        $this->assertSame('456', $ripple->id());
    }

    public function testTitle(): void
    {
        // track
        $ripple = new Ripple;
        $response = '<meta property="og:title" content="Foo Title">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('Foo Title', $ripple->title());

        // album
        $ripple = new Ripple;
        $response = '<meta property="og:title" content="Foo Album">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_ALBUM);
        $this->assertSame('Foo Album', $ripple->title());
    }

    public function testImage(): void
    {
        // track
        $ripple = new Ripple;
        $response = '<meta property="og:image" content="https://image.example.com/123.jpg">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('https://image.example.com/123.jpg', $ripple->image());

        // album
        $ripple = new Ripple;
        $response = '<meta property="og:image" content="https://image.example.com/456.jpg">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_ALBUM);
        $this->assertSame('https://image.example.com/456.jpg', $ripple->image());
    }

    public function testEmbed(): void
    {
        // track
        $ripple = new Ripple;
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/track=123/">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/track=123/', $ripple->embed());

        // album
        $ripple = new Ripple;
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/album=456/">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_ALBUM);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/album=456/', $ripple->embed());

        // track with options
        $ripple = new Ripple;
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/track=123/">';
        $ripple->options(['response' => $response, 'embed' => ['Bandcamp' => 'size=large/']]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/track=123/size=large/', $ripple->embed());

        // album with options
        $ripple = new Ripple;
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/album=456/">';
        $ripple->options(['response' => $response, 'embed' => ['Bandcamp' => 'size=large/']]);
        $ripple->request(self::URL_ALBUM);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/album=456/size=large/', $ripple->embed());

        // track with arguments
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/track=123/', $ripple->embed(self::URL_TRACK, '123'));

        // album with arguments
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/album=456/', $ripple->embed(self::URL_ALBUM, '456'));

        // track with options and arguments
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['Bandcamp' => 'size=large/']]);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/track=123/size=large/', $ripple->embed(self::URL_TRACK, '123'));

        // album with options and arguments
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['Bandcamp' => 'size=large/']]);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/album=456/size=large/', $ripple->embed(self::URL_ALBUM, '456'));
    }
}
