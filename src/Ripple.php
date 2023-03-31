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
        'souterraine\.biz|'.
        'wrwtfww\.com';

    /** @var array<string, string> */
    protected const PATTERNS = [
        Providers\Bandcamp::class => 'https?://([a-z0-9-]+\.)?('.self::BANDCAMP_HOSTS.')/((track|album)/[\w-]+|releases)',
        Providers\SoundCloud::class => 'https://soundcloud\.com/[\w-]+/(sets/)?[\w-]+',
        Providers\Vimeo::class => 'https://vimeo\.com/[0-9]+',
        Providers\YouTube::class => 'https://(www\.)?(youtube\.com/watch\?v=|youtube\.com/playlist\?list=|youtu\.be/)[\w-]+',
    ];

    /** @var array<string, string|array<int|string, int|string|bool>> */
    private array $options = [];

    private ProviderInterface|null $provider = null;

    /**
     * @param array<string, string|array<int|string, int|string|bool>> $options
     */
    public function options(array $options): void
    {
        $this->options = $options;
    }

    public function request(string $url): void
    {
        foreach (static::PATTERNS as $provider => $pattern) {
            if (preg_match('#\A'.$pattern.'\z#', $url)) {
                $instance = new $provider($url, $this->options);

                if ($instance instanceof ProviderInterface) {
                    $this->provider = $instance;

                    break;
                }
            }
        }
    }

    public function provider(): string|null
    {
        if ($this->provider instanceof ProviderInterface) {
            return basename(str_replace('\\', '/', get_class($this->provider)));
        }

        return null;
    }

    /**
     * @param string $method
     * @param array<never> $args
     * @return string|null
     */
    public function __call(string $method, array $args): string|null
    {
        if ($this->provider instanceof ProviderInterface) {
            return $this->provider->$method();
        }

        return null;
    }

    public function embed(string|null $url = null, string|null $id = null): string|null
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
     * @return array<int, string>
     */
    public static function providers(): array
    {
        $providers = [];

        foreach (array_keys(static::PATTERNS) as $provider) {
            $providers[] = basename(str_replace('\\', '/', $provider));
        }

        return $providers;
    }
}
