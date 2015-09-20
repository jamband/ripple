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
 * url pattern 1: https://{subdomain}.bandcamp.com/track/{title}
 * url pattern 2: https://{domain}/track/{title}
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
     * @param Ripple $ripple
     * @return string|null
     */
    public static function id(Ripple $ripple)
    {
        if (isset($ripple->content)) {
            $meta = $ripple->content->filter('meta[property="og:video"]');
            if ($meta->count() > 0) {
                preg_match('/track\=([1-9][0-9]+)?/', $meta->attr('content'), $matches);

                if (!empty($matches)) {
                    return array_pop($matches);
                }
            }
        }
    }

    /**
     * @param Ripple $ripple
     * @return string|null
     */
    public static function title(Ripple $ripple)
    {
        if (isset($ripple->content)) {
            $meta = $ripple->content->filter('meta[name="title"]');
            if ($meta->count() > 0) {
                return $meta->attr('content');
            }
        }
    }

    /**
     * @param Ripple $ripple
     * @return string|null
     */
    public static function image(Ripple $ripple)
    {
        if (isset($ripple->content)) {
            $link = $ripple->content->filter('link[rel="image_src"]');
            if ($link->count() > 0) {
                return $link->attr('href');
            }
        }
    }

    /**
     * @param string $id
     * @see https://bandcamp.com/help/audio_basics#autostart
     * @return string
     */
    public static function embed($id)
    {
        return "https://bandcamp.com/EmbeddedPlayer/track=$id/";
    }
}
