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

class VimeoTest extends \PHPUnit_Framework_TestCase
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
            ['https://vimeo.com/0123456789', false],
            ['https://vimeo.com/'.static::id().'?', false],
            ['https://vimeo.com/'.static::id().'/', false],
            ['https://vimeo.com/'.static::id().'&query=value', false],
            ['https://vimeo.com/'.static::id().'#fragment', false],
            // success
            ['https://vimeo.com/'.static::id(), true],
            ['https://www.vimeo.com/'.static::id(), true],
            ['http://vimeo.com/'.static::id(), true],
            ['http://www.vimeo.com/'.static::id(), true],
        ];
    }

    public function testId()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/Vimeo.php'));

        $ripple = new Ripple('https://vimeo.com/1234567890');
        $ripple->request($client);

        $this->assertSame('1234567890', $ripple->id());
    }

    public function testTitle()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/Vimeo.php'));

        $ripple = new Ripple('https://vimeo.com/'.static::id());
        $ripple->request($client);

        $this->assertSame('Vimeo Title', $ripple->title());
    }

    public function testImage()
    {
        $client = new Client();
        $client->setClient($this->getGuzzle(require __DIR__.'/response/Vimeo.php'));

        $ripple = new Ripple('https://vimeo.com/'.static::id());
        $ripple->request($client);

        $this->assertSame('vimeo_thumbnail.jpg', $ripple->image());
    }

    public function testEmbed()
    {
        $id = static::id();

        $ripple = new Ripple();
        $this->assertSame("https://player.vimeo.com/video/$id", $ripple->embed('Vimeo', $id));

        $ripple = new Ripple();
        $ripple->setEmbedParams(['Vimeo' => '?autoplay=1']);
        $this->assertSame("https://player.vimeo.com/video/$id?autoplay=1", $ripple->embed('Vimeo', $id));
    }

    /**
     * Generate Vimeo like ID. ([1-9][0-9]+)
     * @return string
     */
    private static function id()
    {
        return (string)mt_rand(10000000, 99999999);
    }
}
