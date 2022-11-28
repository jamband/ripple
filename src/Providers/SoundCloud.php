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
 * url pattern 1: https://soundcloud.com/{account}/{title}
 * url pattern 2: https://soundcloud.com/{account}/sets/{title}
 */
final class SoundCloud extends Provider implements ProviderInterface
{
    public function url(): string
    {
        return $this->url;
    }

    public function id(): string|null
    {
        $this->request($this->url);
        $content = $this->query('//meta[@property="twitter:player"]/@content');

        if (null !== $content) {
            preg_match('#/(tracks|playlists)/(?<id>[0-9]+)#', rawurldecode($content), $matches);
        }

        return $matches['id'] ?? null;
    }

    public function title(): string|null
    {
        $this->request($this->url);

        return $this->query('//meta[@property="og:title"]/@content');
    }

    public function image(): string|null
    {
        $this->request($this->url);

        return $this->query('//meta[@property="og:image"]/@content');
    }

    public function embed(string|null $url = null, string|null $id = null): string
    {
        if (!isset($url, $id)) {
            $this->request($this->url);
            $url = $this->url();
            $id = $this->id();
        }

        $type = false !== strpos($url, '/sets/') ? 'playlists' : 'tracks';

        $options = '';
        if (isset($this->options['embed']['SoundCloud'])) {
            $options = '&'.$this->options['embed']['SoundCloud'];
        }

        return "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/$type/$id$options";
    }
}
