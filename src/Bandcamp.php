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
 * Bandcamp class file.
 * url pattern 1: https?://{subdomain}.bandcamp.com/track/{title}
 * url pattern 2: https?://{domain}/track/{title}
 */
class Bandcamp
{
    use Utility;

    /**
     * @var string[]
     */
    public static $hosts = [
        'bandcamp.com',
        'botanicalhouse.net',
        'mamabirdrecordingco.com',
        'souterraine.biz',
    ];

    /**
     * @return string
     */
    public static function validUrlPattern()
    {
        $hosts = str_replace('.', '\.', implode('|', static::$hosts));
        return '#\Ahttps?\://([a-z0-9][a-z0-9-]+\.)?('.$hosts.')/track/[A-Za-z0-9_-]+\z#';
    }

    /**
     * @param string $content
     * @return null|string
     */
    public static function id($content = null)
    {
        $url = static::query($content, 'string(//meta[@property="og:video"]/@content)');
        if (null !== $url) {
            preg_match('#track=([1-9][0-9]+)?#', $url, $matches);
            if (!empty($matches)) {
                return array_pop($matches);
            }
        }
        return null;
    }

    /**
     * @param string $content
     * @return null|string
     */
    public static function title($content = null)
    {
        return static::query($content, 'string(//meta[@property="og:title"]/@content)');
    }

    /**
     * @param string $content
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
        return "https://bandcamp.com/EmbeddedPlayer/track=$id/";
    }
}
