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
 * url pattern 2: https?://{subdomain}.bandcamp.com/album/{title}
 * url pattern 3: https?://{domain}/track/{title}
 * url pattern 4: https?://{domain}/album/{title}
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
        'fikarecordings.com',
        'mamabirdrecordingco.com',
        'maybemars.org',
        'souterraine.biz',
        'sunnysidezone.com',
    ];

    /**
     * @var string
     */
    public static $multiplePattern = '/album/';

    /**
     * @return string
     */
    public static function validUrlPattern()
    {
        $hosts = str_replace('.', '\.', implode('|', static::$hosts));
        return '#\Ahttps?\://([a-z0-9][a-z0-9-]+\.)?('.$hosts.')/(track|album)/[A-Za-z0-9_-]+\z#';
    }

    /**
     * @param string $content
     * @return null|string
     */
    public static function id($content = null)
    {
        $url = static::query($content, 'string(//meta[@property="og:video"]/@content)');
        if (null !== $url) {
            preg_match('#(track|album)=([1-9][0-9]+)?#', $url, $matches);
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
        $image = static::query($content, 'string(//meta[@property="og:image"]/@content)');
        if (null !== $image) {
            return preg_replace('/\Ahttp:/', 'https:', $image);
        }
        return null;
    }

    /**
     * @param string $id
     * @param bool $hasMultiple
     * @return string
     */
    public static function embed($id, $hasMultiple)
    {
        $embed = 'https://bandcamp.com/EmbeddedPlayer';
        return $hasMultiple ? "$embed/album=$id/" : "$embed/track=$id/";
    }
}
