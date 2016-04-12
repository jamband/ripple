<?php

/*
 * This file is part of the ripple library.
 *
 * (c) Tomoki Morita <tmsongbooks215@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace jamband\ripple;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Bandcamp class file.
 * url pattern 1: https?://{subdomain}.bandcamp.com/track/{title}
 * url pattern 2: https?://{domain}/track/{title} (It has not yet implemented)
 */
class Bandcamp
{
    /**
     * @var string
     */
    public static $host = 'bandcamp.com';

    /**
     * @param string $url
     * @return bool
     */
    public static function isValidUrl($url)
    {
        return (bool)preg_match(
            '#\Ahttps?\://[a-z][a-z0-9-]+\.bandcamp\.com/track/[A-Za-z0-9_-]+\z#',
            $url
        );
    }

    /**
     * @param Crawler $crawler
     * @return null|string
     */
    public static function id(Crawler $crawler)
    {
        $meta = $crawler->filter('meta[property="og:video"]');
        if ($meta->count() === 1) {
            preg_match('/track\=([1-9][0-9]+)?/', $meta->attr('content'), $matches);

            if (!empty($matches)) {
                return array_pop($matches);
            }
        }
        return null;
    }

    /**
     * @param Crawler $crawler
     * @return null|string
     */
    public static function title(Crawler $crawler)
    {
        $crawler = $crawler->filter('meta[property="og:title"]');
        return $crawler->count() === 1 ? $crawler->attr('content') : null;
    }

    /**
     * @param Crawler $crawler
     * @return null|string
     */
    public static function image(Crawler $crawler)
    {
        $crawler = $crawler->filter('meta[property="og:image"]');
        return $crawler->count() === 1 ? $crawler->attr('content') : null;
    }

    /**
     * @param string $id
     * @return string
     * @link https://bandcamp.com/help/audio_basics#autostart
     */
    public static function embed($id)
    {
        return "https://bandcamp.com/EmbeddedPlayer/track=$id/";
    }
}
