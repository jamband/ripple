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
 * YouTube class file.
 * url pattern: https://www.youtube.com/watch?v={id}
 */
class YouTube
{
    /**
     * @var string
     */
    public static $host = 'youtube.com';

    /**
     * @var string
     */
    public static $endpoint = 'http://www.youtube.com/oembed?url=';

    /**
     * @param Ripple $ripple
     * @return bool
     */
    public static function isValidUrl(Ripple $ripple)
    {
        return (bool)preg_match(
            '#\Ahttps?\://(www\.)?youtube\.com/watch\?v\=[A-Za-z0-9_-]+\z#',
            $ripple->url
        );
    }

    /**
     * @var Ripple $ripple
     * @return string|null
     */
    public static function id(Ripple $ripple)
    {
        if (isset($ripple->url)) {
            parse_str(parse_url($ripple->url, PHP_URL_QUERY), $query);

            if (isset($query['v'])) {
                return $query['v'];
            }
        }

    }

    /**
     * @param Ripple $ripple
     * @return string|null
     */
    public static function title(Ripple $ripple)
    {
        if (isset($ripple->content->title)) {
            return $ripple->content->title;
        }
    }

    /**
     * @param Ripple $ripple
     * @return string|null
     */
    public static function image(Ripple $ripple)
    {
        if (isset($ripple->content->thumbnail_url)) {
            return $ripple->content->thumbnail_url;
        }
    }

    /**
     * @param string $id
     * @return string
     */
    public static function embed($id)
    {
        return "https://www.youtube.com/embed/$id";
    }
}
