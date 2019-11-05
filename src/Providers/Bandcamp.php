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
 * url pattern 3: https?://{domain}/track/{title}
 * url pattern 4: https?://{domain}/album/{title}
 */
final class Bandcamp extends Provider implements ProviderInterface
{
    /**
     * @return string
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function id(): ?string
    {
        $this->request($this->url);
        $content = $this->query('//meta[@property="og:video"]/@content');

        if (null !== $content) {
            preg_match('#(track|album)=(?<id>[0-9]+)#', $content, $matches);
        }

        return $matches['id'] ?? null;
    }

    /**
     * @return string|null
     */
    public function title(): ?string
    {
        $this->request($this->url);

        return $this->query('//meta[@property="og:title"]/@content');
    }

    /**
     * @return string|null
     */
    public function image(): ?string
    {
        $this->request($this->url);
        $content = $this->query('//meta[@property="og:image"]/@content');

        if (null !== $content) {
            return preg_replace('#\Ahttp:#', 'https:', $content);
        }

        return null;
    }

    /**
     * @param string|null $url
     * @param string|null $id
     * @return string
     */
    public function embed(?string $url = null, ?string $id = null): string
    {
        if (!isset($url, $id)) {
            $this->request($this->url);
            $url = $this->url();
            $id = $this->id();
        }

        $type = false !== strpos($url, '/album/') ? 'album' : 'track';
        $options = $this->options['embed']['Bandcamp'] ?? '';

        return "https://bandcamp.com/EmbeddedPlayer/$type=$id/$options";
    }
}
