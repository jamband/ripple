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
 * url pattern 1: https://vimeo.com/{id}
 *
 * Vimeo's playlists is not implemented. The reasons are as follows:
 * https://vimeo.com/album/{id} is work. But playlists can not get title and thumbnails when using oembed.
 * https://vimeo.com/album/{id}/video/{id} is work. But this will get one video information, not a playlist.
 */
final class Vimeo extends Provider implements ProviderInterface
{
    private const ENDPOINT = 'https://vimeo.com/api/oembed.json?url=';

    public function url(): string
    {
        return $this->url;
    }

    public function id(): string|null
    {
        $url = parse_url($this->url, PHP_URL_PATH);

        if (is_string($url)) {
            [$id] = explode('/', trim($url, '/'));

            return $id;
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
        if (!isset($id)) {
            $this->request($this->url);
            $id = $this->id();
        }

        $options = '';
        if (isset($this->options['embed']['Vimeo'])) {
            $options = '&'.$this->options['embed']['Vimeo'];
        }

        return "https://player.vimeo.com/video/$id?rel=0$options";
    }
}
