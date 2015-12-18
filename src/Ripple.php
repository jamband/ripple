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

use stdClass;
use Goutte\Client;

/**
 * Ripple class file.
 */
class Ripple
{
    use Utility;

    private $url;

    private static $providers = [
        'Bandcamp' => __NAMESPACE__.'\Bandcamp',
        'SoundCloud' => __NAMESPACE__.'\SoundCloud',
        'Vimeo' => __NAMESPACE__.'\Vimeo',
        'YouTube' => __NAMESPACE__.'\YouTube',
    ];

    private $provider;
    private $content;
    private $embedParams;

    /**
     * @param string $url
     */
    public function __construct($url = null)
    {
        $this->url = (string)$url;
        $domain = static::getDomain($this->url);

        foreach (static::$providers as $provider => $class) {
            if (preg_match('/\A('.str_replace('.', '\.', $class::$host).')\z/', $domain)) {
                $this->provider = $provider;
                break;
            }
        }
    }

    /**
     * @param string $method
     * @param array $args
     * @return string|null
     */
    public function __call($method, array $args)
    {
        if (isset($this->provider)) {
            $class = static::$providers[$this->provider];
            return $class::$method($this->content);
        }
    }

    /**
     * @return string|null
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
        if (isset($this->provider)) {
            $class = static::$providers[$this->provider];
            return $class::isValidUrl($this->url);
        }
        return false;
    }

    /**
     * @param Client $client
     */
    public function request(Client $client)
    {
        if (isset($this->provider)) {
            $class = static::$providers[$this->provider];

            if (isset($class::$endpoint)) {
                $client->request('GET', $class::$endpoint.rawurlencode($this->url));
                $this->content = json_decode($client->getResponse()->getContent());
            } else {
                $this->content = $client->request('GET', $this->url);
            }
        }
    }

    /**
     * Returns HTML embed of the track.
     * @param string $provider
     * @param string $id
     * @return string|null
     */
    public function embed($provider = null, $id = null)
    {
        $embed = function ($provider, $embed) {
            return isset($this->embedParams[$provider])
                ? $embed.$this->embedParams[$provider]
                : $embed;
        };
        if (isset($provider, $id) && in_array($provider, static::providers(), true)) {
            $class = static::$providers[$provider];
            return $embed($provider, $class::embed((string)$id));
        }
        if (isset($this->content)) {
            $class = static::$providers[$this->provider];
            return $embed($this->provider, $class::embed($class::id($this->content)));
        }
    }

    /**
     * Sets HTML embed parameters of the track.
     * @param array $embedParams
     */
    public function setEmbedParams(array $embedParams)
    {
        $this->embedParams = $embedParams;
    }

    /**
     * Returns all providers.
     * @return array
     */
    public static function providers()
    {
        return array_keys(static::$providers);
    }
}
