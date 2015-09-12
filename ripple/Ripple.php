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
use Symfony\Component\DomCrawler\Crawler;

/**
 * Ripple class file.
 */
class Ripple
{
    /**
     * @var array all providers (provider => class)
     */
    public static $providers = [
        'Bandcamp' => __NAMESPACE__.'\Bandcamp',
        'SoundCloud' => __NAMESPACE__.'\SoundCloud',
        'Vimeo' => __NAMESPACE__.'\Vimeo',
        'YouTube' => __NAMESPACE__.'\YouTube',
    ];

    /**
     * @var string URL of the provider
     */
    public $url;

    /**
     * @var string the provider name
     */
    public $provider;

    /**
     * @var stdClass|Crawler contents of the track
     */
    public $content;

    private $embedParams;

    /**
     * @param string $url
     */
    public function __construct($url = null)
    {
        $this->url = $url;

        $domain = function ($url) {
            return implode('.', array_slice(
                explode('.', parse_url($url, PHP_URL_HOST)), -2
            ));
        };
        foreach (static::$providers as $provider => $class) {
            if ($domain($url) === $class::$host) {
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
    public function __call($method, array $args = [])
    {
        if (isset($this->url, $this->provider)) {
            $class = static::$providers[$this->provider];
            return $class::$method($this);
        }
    }

    /**
     * @param Client $client
     * @return Ripple|null
     */
    public function request(Client $client)
    {
        if (isset($this->url, $this->provider)) {
            $class = static::$providers[$this->provider];

            if (isset($class::$endpoint)) {
                $client->request('GET', $class::$endpoint.rawurlencode($this->url));
                $this->content = json_decode($client->getResponse()->getContent());
            } else {
                $this->content = $client->request('GET', $this->url);
            }
            if (!isset($this->content)) {
                $this->content = new stdClass();
            }
            return $this;
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
        if (isset($provider, $id)) {
            if (isset(static::$providers[$provider])) {
                $class = static::$providers[$provider];

                return isset($this->embedParams)
                    ? $class::embed($id).$this->embedParams[$provider]
                    : $class::embed($id);
            }

        }
    }

    /**
     * Sets HTML embed parameters of the track.
     * @param array $embedParams
     */
    public function setEmbedParams(array $embedParams = [])
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
