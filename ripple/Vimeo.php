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
 * url pattern: https://vimeo.com/{id}
 */
class Vimeo
{
    /**
     * @var string
     */
    public static $host = 'vimeo.com';

    /**
     * @var string
     * @see https://developer.vimeo.com/apis/oembed
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
     * @var stdClass $content
     * @return string|null
     */
    public static function id(stdClass $content = null)
    {
        if (isset($content->video_id)) {
            return (string)$content->video_id;
        }
    }

    /**
     * @param stdClass $content
     * @return string|null
     */
    public static function title(stdClass $content = null)
    {
        if (isset($content->title)) {
            return $content->title;
        }
    }

    /**
     * @param stdClass $content
     * @return string|null
     */
    public static function image(stdClass $content = null)
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
        return "https://player.vimeo.com/video/$id";
    }
}
