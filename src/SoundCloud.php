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
        $url = static::query($content, 'string(//meta[@property="twitter:player"]/@content)');
        if (null !== $url) {
            preg_match('/\/tracks\/([1-9][0-9]+)?/', rawurldecode($url), $matches);
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
        return static::query($content, 'string(//meta[@property="og:title"]/@content)');
    }

    /**
     * @param $content string
     * @return null|string
     */
    public static function image($content = null)
    {
        return static::query($content, 'string(//meta[@property="og:image"]/@content)');
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
