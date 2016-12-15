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

/**
 * SoundCloud class file.
 * url pattern: https?://soundcloud.com/{account}/{title}
 */
class SoundCloud
{
    use Utility;

    /**
     * @var array
     */
    public static $hosts = [
        'soundcloud.com',
    ];

    /**
     * @return string
     */
    public static function validUrlPattern()
    {
        return '#\Ahttps?\://(www\.)?soundcloud\.com/[A-Za-z0-9-_]+/[A-Za-z0-9-_]+\z#';
    }

    /**
     * @param string $content
     * @return null|string
     */
    public static function id($content = null)
    {
        $nodes = static::query($content, '//meta[@property="twitter:player"]');
        if (1 === $nodes->length) {
            preg_match('/\/tracks\/([1-9][0-9]+)?/', rawurldecode($nodes->item(0)->getAttribute('content')), $matches);
            if (!empty($matches)) {
                return array_pop($matches);
            }
        }
        return null;
    }

    /**
     * @param $content string
     * @return null|string
     */
    public static function title($content = null)
    {
        $nodes = static::query($content, '//meta[@property="og:title"]');
        if (1 === $nodes->length) {
            return $nodes->item(0)->getAttribute('content');
        }
        return null;
    }

    /**
     * @param $content string
     * @return null|string
     */
    public static function image($content = null)
    {
        $nodes = static::query($content, '//meta[@property="og:image"]');
        if (1 === $nodes->length) {
            return $nodes->item(0)->getAttribute('content');
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
