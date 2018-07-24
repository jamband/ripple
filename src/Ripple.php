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
 * @method null|string id()
 * @method null|string title()
 * @method null|string image()
 */
class Ripple
{
    use Utility;

    protected const PROVIDERS = [
        'Bandcamp' => Bandcamp::class,
        'SoundCloud' => SoundCloud::class,
        'Vimeo' => Vimeo::class,
        'YouTube' => YouTube::class,
    ];

    private $content;
    private $embedParams;
    private $provider;
    private $url;

    /**
     * @param null|string $url
     */
    public function __construct(?string $url = null)
    {
        if (null !== $url) {
            foreach (static::PROVIDERS as $provider => $class) {
                if (in_array(static::domain($url), $class::DOMAINS, true)) {
                    $this->url = $url;
                    $this->provider = $provider;

                    break;
                }
            }
        }
    }

    /**
     * @param string $method
     * @param array $args
     * @return null|string
     */
    public function __call(string $method, array $args): ?string
    {
        if (null === $this->provider) {
            return null;
        }

        $class = static::PROVIDERS[$this->provider];

        return $class::$method($this->content);
    }

    /**
     * @return null|string
     */
    public function provider(): ?string
    {
        return $this->provider;
    }

    /**
     * @return bool
     */
    public function isValidUrl(): bool
    {
        if (null === $this->provider) {
            return false;
        }

        $class = static::PROVIDERS[$this->provider];

        return (bool)preg_match($class::validUrlPattern(), $this->url);
    }

    /**
     * @param array $options Set multiple options for a cURL transfer
     * @return void
     * @link http://php.net/manual/en/function.curl-setopt.php
     */
    public function request(array $options = []): void
    {
        if (null !== $this->provider) {
            $class = static::PROVIDERS[$this->provider];

            if (defined("$class::ENDPOINT")) {
                $this->content = static::http($class::ENDPOINT.rawurlencode($this->url), $options);
            } else {
                $this->content = static::http($this->url, $options);
            }
        }
    }

    /**
     * Returns HTML embed of the track.
     *
     * @param null|string $url
     * @param null|string $id
     * @return null|string
     */
    public function embed(?string $url = null, ?string $id = null): ?string
    {
        $hasMultiple = function (string $url, string $pattern): bool {
            return false !== strpos($url, $pattern);
        };

        if (isset($url, $id)) {
            $ripple = new static($url);

            if (null !== $ripple->provider) {
                $class = static::PROVIDERS[$provider = $ripple->provider];
                $embed = $class::embed($id, $hasMultiple($url, $class::MULTIPLE_PATTERN));
            }
        }

        if (null !== $this->content) {
            $class = static::PROVIDERS[$provider = $this->provider];
            $embed = $class::embed($class::id($this->content), $hasMultiple($this->url, $class::MULTIPLE_PATTERN));
        }

        if (!isset($embed, $provider)) {
            return null;
        }

        if (isset($this->embedParams[$provider])) {
            return $embed.$this->embedParams[$provider];
        }

        return $embed;
    }

    /**
     * Sets HTML embed parameters of the track.
     *
     * @param string[] $embedParams
     * @return void
     */
    public function setEmbedParams(array $embedParams = []): void
    {
        $this->embedParams = $embedParams;

    }

    /**
     * Returns all providers.
     *
     * @return string[]
     */
    public static function providers(): array
    {
        return array_keys(static::PROVIDERS);
    }
}
