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
 * Vimeo class file.
 * url pattern: https?://vimeo.com/{id}
 */
class Vimeo
{
    /**
     * @var string
     */
    public static $host = 'vimeo.com';

    /**
     * @var string
     * @link https://developer.vimeo.com/apis/oembed
     */
    public static $endpoint = 'http://vimeo.com/api/oembed.json?url=';

    /**
     * @param string $url
     * @return bool
     */
    public static function isValidUrl($url)
    {
        return (bool)preg_match(
            '#\Ahttps?\://(www\.)?vimeo\.com/[1-9][0-9]+\z#',
            $url
        );
    }

    /**
     * @param stdClass $content
     * @return null|string
     */
    public static function id(stdClass $content = null)
    {
        return isset($content->video_id) ? (string)$content->video_id : null;
    }

    /**
     * @param stdClass $content
     * @return null|string
     */
    public static function title(stdClass $content = null)
    {
        return isset($content->title) ? $content->title : null;
    }

    /**
     * @param stdClass $content
     * @return null|string
     */
    public static function image(stdClass $content = null)
    {
        return isset($content->thumbnail_url) ? $content->thumbnail_url : null;
    }

    /**
     * @param string $id
     * @return string
     */
    public static function embed($id)
    {
        return "https://player.vimeo.com/video/$id";
    }
}
