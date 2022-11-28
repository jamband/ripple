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
 * url pattern 1: https://{subdomain}.bandcamp.com/track/{title}
 * url pattern 2: https://{subdomain}.bandcamp.com/album/{title}
 * url pattern 3: https://{subdomain}.bandcamp.com/releases
 * url pattern 4: https?://{domain}/track/{title}
 * url pattern 5: https?://{domain}/album/{title}
 * url pattern 6: https?://{domain}/releases
 */
final class Bandcamp extends Provider implements ProviderInterface
{
    public function url(): string
    {
        return $this->url;
    }

    public function id(): string|null
    {
        $this->request($this->url);
        $content = $this->query('//meta[@property="og:video"]/@content');

        if (null !== $content) {
            preg_match('#(track|album)=(?<id>[0-9]+)#', $content, $matches);
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
        $content = $this->query('//meta[@property="og:image"]/@content');

        if (null !== $content) {
            return preg_replace('#\Ahttp:#', 'https:', $content);
        }

        return null;
    }

    public function embed(string|null $url = null, string|null $id = null): string
    {
        if (!isset($url, $id)) {
            $this->request($this->url);
            $url = $this->url();
            $id = $this->id();
        }

        $type = str_contains($url, '/album/') ? 'album' : 'track';
        $options = $this->options['embed']['Bandcamp'] ?? '';

        return "https://bandcamp.com/EmbeddedPlayer/$type=$id/$options";
    }
}
