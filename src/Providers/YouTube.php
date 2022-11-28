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

namespace Jamband\Ripple\Providers;

/**
 * url pattern 1: https://www.youtube.com/watch?v={id}
 * url pattern 2: https://www.youtube.com/playlist?list={id}
 * url pattern 3: https://youtu.be/{id}
 */
final class YouTube extends Provider implements ProviderInterface
{
    private const ENDPOINT = 'https://www.youtube.com/oembed?url=';

    public function url(): string
    {
        $host = parse_url($this->url, PHP_URL_HOST);

        if ('youtu.be' === $host) {
            $path = parse_url($this->url, PHP_URL_PATH);

            if (is_string($path)) {
                return 'https://www.youtube.com/watch?v='.trim($path, '/');
            }
        }

        return $this->url;
    }

    public function id(): string|null
    {
        $host = parse_url($this->url, PHP_URL_HOST);

        if ('www.youtube.com' === $host) {
            $query = parse_url($this->url, PHP_URL_QUERY);

            if (is_string($query)) {
                parse_str($query, $queries);

                if (isset($queries['v']) && is_string($queries['v'])) {
                    return $queries['v'];
                }

                if (isset($queries['list']) && is_string($queries['list'])) {
                    return $queries['list'];
                }
            }
        }

        if ('youtu.be' === $host) {
            $path = parse_url($this->url, PHP_URL_PATH);

            if (is_string($path)) {
                return trim($path, '/');
            }
        }

        return null;
    }

    public function title(): string|null
    {
        $this->request(self::ENDPOINT.rawurlencode($this->url));

        if (null !== $this->response) {
            $content = json_decode($this->response);

            return $content->title ?? null;
        }

        return null;
    }

    public function image(): string|null
    {
        $this->request(self::ENDPOINT.rawurlencode($this->url));

        if (null !== $this->response) {
            $content = json_decode($this->response);

            return $content->thumbnail_url ?? null;
        }

        return null;
    }

    public function embed(string|null $url = null, string|null $id = null): string
    {
        if (!isset($url, $id)) {
            $this->request($this->url);
            $id = $this->id();
            $url = $this->url();
        }

        $options = '';
        if (isset($this->options['embed']['YouTube'])) {
            $options = '&'.$this->options['embed']['YouTube'];
        }

        $embed = 'https://www.youtube.com/embed';

        if (false !== strpos($url, '?list=')) {
            return "$embed/videoseries?list=$id&rel=0$options";
        }

        return "$embed/$id?rel=0$options";
    }
}
