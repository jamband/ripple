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
 * url pattern: https://soundcloud.com/{account}/{title}
 */
class SoundCloud
{
    /**
     * @var string
     */
    public static $host = 'soundcloud.com';

    /**
     * @param string $url
     * @return bool
     */
    public static function isValidUrl($url)
    {
        return (bool)preg_match(
            '#\Ahttps?\://(www\.)?soundcloud\.com/[A-Za-z0-9-_]+/[A-Za-z0-9-_]+\z#',
            $url
        );
    }

    /**
     * @param Crawler $crawler
     * @return string|null
     */
    public static function id(Crawler $crawler)
    {
        $meta = $crawler->filter('meta[name="twitter:audio:source"]');
        if ($meta->count() === 1) {
            preg_match('/sounds\:([1-9][0-9]+)?/', $meta->attr('content'), $matches);

            if (!empty($matches)) {
                return array_pop($matches);
            }
        }
    }

    /**
     * @param Crawler $crawler
     * @return string|null
     */
    public static function title(Crawler $crawler)
    {
        $meta = $crawler->filter('meta[property="og:title"]');
        if ($meta->count() === 1) {
            return $meta->attr('content');
        }
    }

    /**
     * @param Crawler $crawler
     * @return string|null
     */
    public static function image(Crawler $crawler)
    {
        $meta = $crawler->filter('meta[property="og:image"]');
        if ($meta->count() === 1) {
            return $meta->attr('content');
        }
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
