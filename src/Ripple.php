<?php

/*
 * This file is part of the ripple library.
 *
 * (c) Tomoki Morita <tmsongbooks215@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace jamband\ripple;

/**
 * Ripple class file.
 * @method null|string id()
 * @method null|string title()
 * @method null|string image()
 */
class Ripple
{
    use Utility;

    private static $providers = [
        'Bandcamp' => __NAMESPACE__.'\Bandcamp',
        'SoundCloud' => __NAMESPACE__.'\SoundCloud',
        'Vimeo' => __NAMESPACE__.'\Vimeo',
        'YouTube' => __NAMESPACE__.'\YouTube',
    ];

    private $url;
    private $provider;
    private $content;
    private $embedParams;

    /**
     * @param string $url
     */
    public function __construct($url = null)
    {
        $this->url = (string)$url;

        foreach (static::$providers as $provider => $class) {
            if (in_array(static::domain($this->url), $class::$hosts, true)) {
                $this->provider = $provider;
                break;
            }
        }
    }

    /**
     * @param string $method
     * @param array $args
     * @return null|string
     */
    public function __call($method, array $args)
    {
        if (null === $this->provider) {
            return null;
        }
        $class = static::$providers[$this->provider];
        return $class::$method($this->content);
    }

    /**
     * @return null|string
     */
    public function provider()
    {
        return $this->provider;
    }

    /**
     * @return bool
     */
    public function isValidUrl()
    {
        if (null === $this->provider) {
            return false;
        }
        $class = static::$providers[$this->provider];
        return (bool)preg_match($class::validUrlPattern(), $this->url);
    }

    /**
     * @param array $options Set multiple options for a cURL transfer
     * @link http://php.net/manual/en/function.curl-setopt.php
     */
    public function request(array $options = [])
    {
        if (null !== $this->provider) {
            $class = static::$providers[$this->provider];

            if (isset($class::$endpoint)) {
                $this->content = json_decode(static::http($class::$endpoint.rawurlencode($this->url), $options));
            } else {
                $this->content = static::http($this->url, $options);
            }
        }
    }

    /**
     * Returns HTML embed of the track.
     * @param string $provider
     * @param string $id
     * @return null|string
     */
    public function embed($provider = null, $id = null)
    {
        $embed = function ($provider, $embed) {
            if (isset($this->embedParams[$provider])) {
                return $embed.$this->embedParams[$provider];
            }
            return $embed;
        };
        if (isset($provider, $id) && in_array($provider, static::providers(), true)) {
            $class = static::$providers[$provider];
            return $embed($provider, $class::embed((string)$id));
        }
        if (isset($this->content)) {
            $class = static::$providers[$this->provider];
            return $embed($this->provider, $class::embed($class::id($this->content)));
        }
        return null;
    }

    /**
     * Sets HTML embed parameters of the track.
     * @param string[] $embedParams
     */
    public function setEmbedParams(array $embedParams = [])
    {
        $this->embedParams = $embedParams;
    }

    /**
     * Returns all providers.
     * @return string[]
     */
    public static function providers()
    {
        return array_keys(static::$providers);
    }
}
