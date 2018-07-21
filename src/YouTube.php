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

use stdClass;

/**
 * YouTube class file.
 * url pattern 1: https?://www.youtube.com/watch?v={id}
 * url pattern 2: https?://www.youtube.com/playlist?list={id}
 * url pattern 3: https?://youtu.be/{id}
 */
class YouTube implements ProviderInterface
{
    use Utility;

    public const DOMAINS = [
        'youtube.com',
        'youtu.be',
    ];

    public const MULTIPLE_PATTERN = '?list=';
    public const ENDPOINT = 'https://www.youtube.com/oembed?url=';

    /**
     * @return string
     */
    public static function validUrlPattern(): string
    {
        return '#\Ahttps?\://(www\.)?(youtube\.com/watch\?v=|youtube\.com/playlist\?list=|youtu\.be/)[A-Za-z0-9_-]+\z#';
    }

    /**
     * @param null|string $content
     * @return null|string
     */
    public static function id(?string $content): ?string
    {
        $content = json_decode($content);

        if (isset($content->html)) {
            preg_match('#embed/(videoseries\?list=)?([A-Za-z0-9_-]+)?#', $content->html, $matches);

            if (!empty($matches)) {
                return array_pop($matches);
            }
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
        $embed = 'https://www.youtube.com/embed';

        return $hasMultiple ? "$embed/videoseries?list=$id&rel=0" : "$embed/$id?rel=0";
    }
}
