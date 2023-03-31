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

namespace Tests;

use Jamband\Ripple\Ripple;
use PHPUnit\Framework\TestCase;

class RippleTest extends TestCase
{
    public function testOptionsWithEmptyStringResponse(): void
    {
        $ripple = new Ripple();
        $ripple->options(['response' => '']);
        $ripple->request('https://foo.bandcamp.com/track/title');
        $this->assertNull($ripple->id());
    }

    public function testOptionsWithResponse(): void
    {
        $ripple = new Ripple();
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/track=123/">';
        $ripple->options(['response' => $response]);
        $ripple->request('https://foo.bandcamp.com/track/title');
        $this->assertSame('123', $ripple->id());
    }

    public function testOptionsWithResponseAndEmbed(): void
    {
        $ripple = new Ripple();
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/track=123/">';
        $ripple->options(['response' => $response, 'embed' => ['Bandcamp' => 'size=large/']]);
        $ripple->request('https://foo.bandcamp.com/track/title');
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/track=123/size=large/', $ripple->embed());
    }

    public function testRequestFails(): void
    {
        $ripple = new Ripple();
        $ripple->request('https://example.com/track/title');
        $this->assertNull($ripple->provider());
    }

    public function testRequest(): void
    {
        $ripple = new Ripple();
        $ripple->request('https://foo.bandcamp.com/track/title');
        $this->assertSame('Bandcamp', $ripple->provider());
    }

    /**
     * @dataProvider providerProvider
     */
    public function testProvider(string $url, ?string $provider): void
    {
        $ripple = new Ripple();
        $ripple->request($url);
        $this->assertSame($provider, $ripple->provider());
    }

    /**
     * @return array<int, array<int, string|null>>
     */
    public function providerProvider(): array
    {
        return [
            ['https://example.com/track/title', null],

            ['https://foo.bandcamp.com/bar/title', null],
            ['https://foo.bandcamp.com/track/title', 'Bandcamp'],
            ['https://foo.bandcamp.com/album/title', 'Bandcamp'],
            ['https://foo.bandcamp.com/releases', 'Bandcamp'],
            ['https://shop.fikarecordings.com/track/title', 'Bandcamp'],
            ['https://shop.fikarecordings.com/album/title', 'Bandcamp'],
            ['https://shop.fikarecordings.com/releases', 'Bandcamp'],
            ['https://tunes.mamabirdrecordingco.com/track/title', 'Bandcamp'],
            ['https://tunes.mamabirdrecordingco.com/album/title', 'Bandcamp'],
            ['https://tunes.mamabirdrecordingco.com/releases', 'Bandcamp'],
            ['https://downloads.maybemars.org/track/title', 'Bandcamp'],
            ['https://downloads.maybemars.org/album/title', 'Bandcamp'],
            ['https://downloads.maybemars.org/releases', 'Bandcamp'],
            ['https://souterraine.biz/track/title', 'Bandcamp'],
            ['https://souterraine.biz/album/title', 'Bandcamp'],
            ['https://souterraine.biz/releases', 'Bandcamp'],
            ['https://wrwtfww.com/track/title', 'Bandcamp'],
            ['https://wrwtfww.com/album/title', 'Bandcamp'],
            ['https://wrwtfww.com/releases', 'Bandcamp'],

            ['https://soundcloud.com/foo', null],
            ['https://soundcloud.com/foo/title', 'SoundCloud'],
            ['https://soundcloud.com/foo/sets/title', 'SoundCloud'],

            ['https://vimeo.com/foo', null],
            ['https://vimeo.com/123', 'Vimeo'],

            ['https://www.youtube.com/channel/foo123', null],
            ['https://www.youtube.com/watch?v=foo123', 'YouTube'],
            ['https://youtube.com/watch?v=foo123', 'YouTube'],
            ['https://youtu.be/foo123', 'YouTube'],
            ['https://www.youtube.com/playlist?list=foo123', 'YouTube'],
        ];
    }

    public function testUrl(): void
    {
        $ripple = new Ripple();
        $this->assertNull($ripple->url());

        $ripple->request('https://foo.bandcamp.com/track/title');
        $this->assertSame('https://foo.bandcamp.com/track/title', $ripple->url());
    }

    public function testId(): void
    {
        $ripple = new Ripple();
        $this->assertNull($ripple->id());

        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/track=123/">';
        $ripple->options(['response' => $response]);
        $ripple->request('https://foo.bandcamp.com/track/title');
        $this->assertSame('123', $ripple->id());
    }

    public function testTitle(): void
    {
        $ripple = new Ripple();
        $this->assertNull($ripple->title());

        $response = '<meta property="og:title" content="Foo Title">';
        $ripple->options(['response' => $response]);
        $ripple->request('https://foo.bandcamp.com/track/title');
        $this->assertSame('Foo Title', $ripple->title());
    }

    public function testTitleWithMultibyteString(): void
    {
        $ripple = new Ripple();
        $this->assertNull($ripple->title());

        $response = '<meta property="og:title" content="坂本九 - 上を向いて歩こう">';
        $ripple->options(['response' => $response]);
        $ripple->request('https://foo.bandcamp.com/track/title');
        $this->assertSame('坂本九 - 上を向いて歩こう', $ripple->title());
    }

    public function testImage(): void
    {
        $ripple = new Ripple();
        $this->assertNull($ripple->image());

        $response = '<meta property="og:image" content="https://image.example.com/123.jpg">';
        $ripple->options(['response' => $response]);
        $ripple->request('https://foo.bandcamp.com/track/title');
        $this->assertSame('https://image.example.com/123.jpg', $ripple->image());
    }

    public function testEmbedWithNonRequest(): void
    {
        $ripple = new Ripple();
        $this->assertNull($ripple->embed());
    }

    public function testEmbedWithResponse(): void
    {
        $ripple = new Ripple();
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/track=123/">';
        $ripple->options(['response' => $response]);
        $ripple->request('https://foo.bandcamp.com/track/title');
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/track=123/', $ripple->embed());
    }

    public function testEmbedWithEmptyStringResponseWithEmbed(): void
    {
        $ripple = new Ripple();
        $ripple->options(['response' => '', 'embed' => ['Bandcamp' => 'size=large/']]);
        $url = 'https://foo.bandcamp.com/track/title';
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/track=123/size=large/', $ripple->embed($url, '123'));
    }

    public function testEmbedWithResponseAndEmbed(): void
    {
        $ripple = new Ripple();
        $response = '<meta property="og:video" content="https://example.com/EmbeddedPlayer/v=2/track=123/">';
        $ripple->options(['response' => $response, 'embed' => ['Bandcamp' => 'size=large/']]);
        $ripple->request('https://foo.bandcamp.com/track/title');
        $this->assertSame('https://bandcamp.com/EmbeddedPlayer/track=123/size=large/', $ripple->embed());
    }

    public function testProviders(): void
    {
        $this->assertSame(['Bandcamp', 'SoundCloud', 'Vimeo', 'YouTube'], Ripple::providers());
    }
}
