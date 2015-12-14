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
 * url pattern 1: https://www.youtube.com/watch?v={id}
 * url pattern 2: https://youtu.be/{id}
 */
class YouTube
{
    use Utility;

    /**
     * @var string
     */
    public static $host = 'youtube.com|youtu.be';

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
        $domain = static::getDomain($url);

        if ($domain === 'youtube.com') {
            $pattern = '#\Ahttps?\://(www\.)?youtube\.com/watch\?v\=[A-Za-z0-9_-]+\z#';
        }
        if ($domain === 'youtu.be') {
            $pattern = '#\Ahttps?\://youtu\.be/[A-Za-z0-9_-]+\z#';
        }
        if (isset($pattern)) {
            return (bool)preg_match($pattern, $url);
        }
        return false;
    }

    /**
     * @var stdClass $content
     * @return string|null
     */
    public static function id(stdClass $content = null)
    {
        if (isset($content->html)) {
            preg_match('/embed\/([A-Za-z0-9_-]+)?\?/', $content->html, $matches);

            if (!empty($matches)) {
                return array_pop($matches);
            }
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
        return "https://www.youtube.com/embed/$id";
    }
}
