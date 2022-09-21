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
    private const URL_RELEASES = 'https://foo.bandcamp.com/releases';

    public function testUrlWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_TRACK);
        $this->assertSame(self::URL_TRACK, $ripple->url());
    }

    public function testUrlWithAlbumUrl(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_ALBUM);
        $this->assertSame(self::URL_ALBUM, $ripple->url());
    }

    public function testUrlWithReleasesUrl(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $ripple->request(self::URL_RELEASES);
        $this->assertSame(self::URL_RELEASES, $ripple->url());
    }

    public function testIdWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/track=123/">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('123', $ripple->id());
    }

    public function testIdWithAlbumUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/album=456/">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_ALBUM);
        $this->assertSame('456', $ripple->id());
    }

    public function testTitleWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="og:title" content="Foo Title">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('Foo Title', $ripple->title());
    }

    public function testTitleWithAlbumUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="og:title" content="Foo Album">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_ALBUM);
        $this->assertSame('Foo Album', $ripple->title());
    }

    public function testImageWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="og:image" content="https://image.example.com/123.jpg">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('https://image.example.com/123.jpg', $ripple->image());
    }

    public function testImageWithAlbumUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="og:image" content="https://image.example.com/456.jpg">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_ALBUM);
        $this->assertSame('https://image.example.com/456.jpg', $ripple->image());
    }

    public function testEmbedWithTrackUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/track=123/">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/track=123/', $ripple->embed());
    }

    public function testEmbedWithAlbumUrl(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/album=456/">';
        $ripple->options(['response' => $response]);
        $ripple->request(self::URL_ALBUM);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/album=456/', $ripple->embed());
    }

    public function testEmbedWithTrackUrlAndOptions(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/track=123/">';
        $ripple->options(['response' => $response, 'embed' => ['Bandcamp' => 'size=large/']]);
        $ripple->request(self::URL_TRACK);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/track=123/size=large/', $ripple->embed());
    }

    public function testEmbedWithAlbumUrlAndOptions(): void
    {
        $ripple = new Ripple;
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/album=456/">';
        $ripple->options(['response' => $response, 'embed' => ['Bandcamp' => 'size=large/']]);
        $ripple->request(self::URL_ALBUM);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/album=456/size=large/', $ripple->embed());
    }

    public function testEmbedWithTrackUrlAndArguments(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/track=123/', $ripple->embed(self::URL_TRACK, '123'));
    }

    public function testEmbedWithAlbumUrlAndArguments(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '']);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/album=456/', $ripple->embed(self::URL_ALBUM, '456'));
    }

    public function testEmbedWithTrackAndOptionsAndArguments(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['Bandcamp' => 'size=large/']]);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/track=123/size=large/', $ripple->embed(self::URL_TRACK, '123'));
    }

    public function testEmbedWithAlbumAndOptionsAndArguments(): void
    {
        $ripple = new Ripple;
        $ripple->options(['response' => '', 'embed' => ['Bandcamp' => 'size=large/']]);
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/album=456/size=large/', $ripple->embed(self::URL_ALBUM, '456'));
    }
}
