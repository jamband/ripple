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
 * url pattern 2: https?://{domain}/track/{title}
 */
class Bandcamp
{
    /**
     * @var array
     */
    public static $hosts = [
        'bandcamp.com',
        'botanicalhouse.net',
        'mamabirdrecordingco.com',
        'souterraine.biz',
    ];

    /**
     * @return boolean
     */
    public static function validUrlPattern()
    {
        $hosts = str_replace('.', '\.', implode('|', static::$hosts));
        return '#\Ahttps?\://([a-z0-9][a-z0-9-]+\.)?('.$hosts.')/track/[A-Za-z0-9_-]+\z#';
    }

    /**
     * @param Crawler $crawler
     * @return null|string
     */
    public static function id(Crawler $crawler)
    {
        $meta = $crawler->filter('meta[property="og:video"]');
        if (1 === $meta->count()) {
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
        if (1 === $crawler->count()) {
            return $crawler->attr('content');
        }
        return null;
    }

    /**
     * @param Crawler $crawler
     * @return null|string
     */
    public static function image(Crawler $crawler)
    {
        $crawler = $crawler->filter('meta[property="og:image"]');
        if (1 === $crawler->count()) {
            return $crawler->attr('content');
        }
        return null;
    }

    /**
     * @param string $id
     * @return string
     */
    public static function embed($id)
    {
        return "https://bandcamp.com/EmbeddedPlayer/track=$id/";
    }
}
