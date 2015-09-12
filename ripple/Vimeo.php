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
     * @param Ripple $ripple
     * @return bool
     */
    public static function isValidUrl(Ripple $ripple)
    {
        return (bool)preg_match(
            '#\Ahttps?\://(www\.)?vimeo\.com/[1-9][0-9]+\z#',
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
            return substr(parse_url($ripple->url, PHP_URL_PATH), 1);
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
        return "https://player.vimeo.com/video/$id";
    }
}
