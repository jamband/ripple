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

namespace Jamband\Ripple;

use Jamband\Ripple\Providers\ProviderInterface;

/**
 * @method string|null url()
 * @method string|null id()
 * @method string|null title()
 * @method string|null image()
 */
class Ripple
{
    protected const BANDCAMP_HOSTS =
        'bandcamp\.com|'.
        'fikarecordings\.com|'.
        'mamabirdrecordingco\.com|'.
        'maybemars\.org|'.
        'souterraine\.biz';

    protected const PATTERNS = [
        Providers\Bandcamp::class => 'https?://([a-z0-9-]+\.)?('.self::BANDCAMP_HOSTS.')/(track|album)/[\w-]+',
        Providers\SoundCloud::class => 'https://soundcloud\.com/[\w-]+/(sets/)?[\w-]+',
        Providers\Vimeo::class => 'https://vimeo\.com/[0-9]+',
        Providers\YouTube::class => 'https://(www\.)?(youtube\.com/watch\?v=|youtube\.com/playlist\?list=|youtu\.be/)[\w-]+',
    ];

    private $options = [];
    private $provider;

    /**
     * @param array $options
     * @return void
     */
    public function options(array $options): void
    {
        $this->options = $options;
    }

    /**
     * @param string $url
     * @return void
     */
    public function request(string $url): void
    {
        foreach (static::PATTERNS as $provider => $pattern) {
            if (preg_match('#\A'.$pattern.'\z#', $url)) {
                $this->provider = new $provider($url, $this->options);

                break;
            }
        }
    }

    /**
     * @return string|null
     */
    public function provider(): ?string
    {
        if ($this->provider instanceof ProviderInterface) {
            return static::classShortName(get_class($this->provider));
        }

        return null;
    }

    /**
     * @param string $method
     * @param array $args
     * @return string|null
     */
    public function __call(string $method, array $args): ?string
    {
        if ($this->provider instanceof ProviderInterface) {
            return $this->provider->$method();
        }

        return null;
    }

    /**
     * @param string|null $url
     * @param string|null $id
     * @return string|null
     */
    public function embed(?string $url = null, ?string $id = null): ?string
    {
        if (isset($url, $id)) {
            $this->request($url);
        }

        if ($this->provider instanceof ProviderInterface) {
            return $this->provider->embed($url, $id);
        }

        return null;
    }

    /**
     * @return string[]
     */
    public static function providers(): array
    {
        $providers = [];

        foreach (array_keys(static::PATTERNS) as $provider) {
            $providers[] = static::classShortName($provider);
        }

        return $providers;
    }

    /**
     * @param string $path
     * @return string
     */
    private static function classShortName(string $path): string
    {
        return basename(str_replace('\\', '/', $path));
    }
}
