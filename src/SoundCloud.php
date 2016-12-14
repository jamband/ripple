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
 * SoundCloud class file.
 * url pattern: https?://soundcloud.com/{account}/{title}
 */
class SoundCloud
{
    /**
     * @var string
     */
    public static $host = 'soundcloud.com';

    /**
     * @return boolean
     */
    public static function validUrlPattern()
    {
        return '#\Ahttps?\://(www\.)?soundcloud\.com/[A-Za-z0-9-_]+/[A-Za-z0-9-_]+\z#';
    }

    /**
     * @param Crawler $crawler
     * @return null|string
     */
    public static function id(Crawler $crawler)
    {
        $meta = $crawler->filter('meta[property="twitter:player"]');
        if ($meta->count() === 1) {
            preg_match('/\/tracks\/([1-9][0-9]+)?/', rawurldecode($meta->attr('content')), $matches);

            if (!empty($matches)) {
                return array_pop($matches);
            }
        }
        return null;
    }

    /**
     * @param Crawler $crawler
     * @return string|null
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
     * @return string|null
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
        return "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/$id";
    }
}
