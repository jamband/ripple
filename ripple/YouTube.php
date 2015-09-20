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

use stdClass;

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
     * @param string $url
     * @return bool
     */
    public static function isValidUrl($url)
    {
        return (bool)preg_match(
            '#\Ahttps?\://(www\.)?youtube\.com/watch\?v\=[A-Za-z0-9_-]+\z#',
            $url
        );
    }

    /**
     * @var stdClass $content
     * @return string|null
     */
    public static function id(stdClass $content)
    {
        parse_str(parse_url($content->url, PHP_URL_QUERY), $query);

        if (isset($query['v'])) {
            return $query['v'];
        }

    }

    /**
     * @param stdClass $content
     * @return string|null
     */
    public static function title(stdClass $content)
    {
        if (isset($content->title)) {
            return $content->title;
        }
    }

    /**
     * @param stdClass $content
     * @return string|null
     */
    public static function image(stdClass $content)
    {
        if (isset($content->thumbnail_url)) {
            return $content->thumbnail_url;
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
