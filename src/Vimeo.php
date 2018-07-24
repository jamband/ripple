<?php

/*
 * This file is part of the ripple library.
 *
 * (c) Tomoki Morita <tmsongbooks215@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace jamband\ripple;

/**
 * url pattern 1: https?://vimeo.com/{id}
 *
 * Vimeo's playlists is not implemented. The reasons are as follows:
 * https://vimeo.com/album/{id} is work. But playlists can not get title and thumbnails when using oembed.
 * https://vimeo.com/album/{id}/video/{id} is work. But this will get one video information, not a playlist.
 */
class Vimeo implements ProviderInterface
{
    public const DOMAINS = [
        'vimeo.com',
    ];

    public const MULTIPLE_PATTERN = '/album/';
    public const ENDPOINT = 'https://vimeo.com/api/oembed.json?url=';

    /**
     * @return string
     */
    public static function validUrlPattern(): string
    {
        return '#\Ahttps?\://(www\.)?vimeo\.com/[1-9][0-9]+\z#';
    }

    /**
     * @param null|string $content
     * @return null|string
     */
    public static function id(?string $content): ?string
    {
        $content = json_decode($content);

        if (isset($content->video_id)) {
            return (string)$content->video_id;
        }

        return null;
    }

    /**
     * @param null|string $content
     * @return null|string
     */
    public static function title(?string $content): ?string
    {
        $content = json_decode($content);

        return $content->title ?? null;
    }

    /**
     * @param null|string $content
     * @return null|string
     */
    public static function image(?string $content): ?string
    {
        $content = json_decode($content);

        return $content->thumbnail_url ?? null;
    }

    /**
     * @param string $id
     * @param bool $hasMultiple
     * @return string
     */
    public static function embed(string $id, bool $hasMultiple): string
    {
        $embed =  'https://player.vimeo.com/video';

        return $hasMultiple ? "$embed/album/$id?rel=0" : "$embed/$id?rel=0";
    }
}
