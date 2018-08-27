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
 * url pattern 1: https?://soundcloud.com/{account}/{title}
 * url pattern 2: https?://soundcloud.com/{account}/sets/{title}
 */
class SoundCloud implements ProviderInterface
{
    use Utility;

    public const DOMAINS = [
        'soundcloud.com',
    ];

    public const MULTIPLE_PATTERN = '/sets/';

    /**
     * @return string
     */
    public static function validUrlPattern(): string
    {
        return '#\Ahttps?\://(www\.)?soundcloud\.com/[A-Za-z0-9-_]+/(sets/)?[A-Za-z0-9-_]+\z#';
    }

    /**
     * @param string $content
     * @return null|string
     */
    public static function id(string $content): ?string
    {
        $url = static::query($content, 'string(//meta[@property="twitter:player"]/@content)');

        if (null !== $url) {
            preg_match('#/(tracks|playlists)/([1-9][0-9]+)?#', rawurldecode($url), $matches);

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
    public static function title(string $content): ?string
    {
        return static::query($content, 'string(//meta[@property="og:title"]/@content)');
    }

    /**
     * @param string $content
     * @return null|string
     */
    public static function image(string $content): ?string
    {
        return static::query($content, 'string(//meta[@property="og:image"]/@content)');
    }


    /**
     * @param string $id
     * @param bool $hasMultiple
     * @return string
     */
    public static function embed(string $id, bool $hasMultiple): string
    {
        $embed = 'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com';

        return $hasMultiple ? "$embed/playlists/$id" : "$embed/tracks/$id";
    }
}
