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
use Goutte\Client;

class YouTubeTest extends \PHPUnit_Framework_TestCase
{
    use ClientTrait;

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
            ['https://www.youtube.com/watch?v='.static::id().'/', false],
            ['https://www.youtube.com/watch?v='.static::id().'?', false],
            ['https://www.youtube.com/watch?v='.static::id().'&query=value', false],
            ['https://www.youtube.com/watch?v='.static::id().'#fragment', false],
            ['https://youtu.be/'.static::id().'/', false],
            ['https://youtu.be/'.static::id().'?', false],
            ['https://youtu.be/'.static::id().'&query=value', false],
            ['https://youtu.be/'.static::id().'#fragment', false],

            // success
            ['https://www.youtube.com/watch?v='.static::id(), true],
            ['https://youtube.com/watch?v='.static::id(), true],
            ['http://www.youtube.com/watch?v='.static::id(), true],
            ['http://youtube.com/watch?v='.static::id(), true],
            ['https://youtu.be/'.static::id(), true],
            ['http://youtu.be/'.static::id(), true],
        ];
    }

    public function testId()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/YouTube.php'));

        $ripple = new Ripple('https://www.youtube.com/watch?v=AbCxYz012_-');
        $ripple->request($client);

        $this->assertSame('AbCxYz012_-', $ripple->id());
    }

    public function testTitle()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/YouTube.php'));

        $ripple = new Ripple('https://www.youtube.com/watch?v='.static::id());
        $ripple->request($client);

        $this->assertSame('YouTube Title', $ripple->title());
    }

    public function testImage()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/YouTube.php'));

        $ripple = new Ripple('https://www.youtube.com/watch?v='.static::id());
        $ripple->request($client);

        $this->assertSame('youtube_thumbnail.jpg', $ripple->image());
    }

    public function testEmbed()
    {
        $id = static::id();

        $ripple = new Ripple();
        $this->assertSame("https://www.youtube.com/embed/$id", $ripple->embed('YouTube', $id));

        $ripple = new Ripple();
        $ripple->setEmbedParams(['YouTube' => '?autoplay=1']);
        $this->assertSame("https://www.youtube.com/embed/$id?autoplay=1", $ripple->embed('YouTube', $id));
    }

    /**
     * Generate YouTube like ID. (e.g. pButZ3Littk, P5xR6zWO_no, _-Hv-oThSwc)
     * @return string
     */
    private static function id()
    {
        return rtrim(strtr(base64_encode(openssl_random_pseudo_bytes(8)), '+/', '-_'), '=');
    }
}
