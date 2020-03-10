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

use DOMDocument;
use DOMXPath;

class Provider
{
    protected $url;
    protected $options;
    protected $response;

    /**
     * @param string $url
     * @param array $options
     */
    public function __construct(string $url, array $options)
    {
        $this->url = $url;
        $this->options = $options;
    }

    /**
     * @param string $url
     */
    protected function request(string $url): void
    {
        if (isset($this->options['response']) && is_string($this->options['response'])) {
            $this->response = $this->options['response'];

        } elseif (null === $this->response) {
            $options = [
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_ENCODING => 'gzip',
                CURLOPT_FAILONERROR => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TCP_FASTOPEN => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => 'Jamband/Ripple BrowserKit',
            ];

            if (isset($this->options['curl']) && is_array($this->options['curl'])) {
                $options = array_replace($options, $this->options['curl']);
            }

            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            curl_close($ch);

            if (false !== $response) {
                $this->response = $response;
            }
        }
    }

    /**
     * @param string $expression
     * @return string|null
     */
    protected function query(string $expression): ?string
    {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = false;

        if (null !== $this->response && '' !== $this->response) {
            $dom->loadHTML(mb_convert_encoding($this->response, 'HTML-ENTITIES'));
        }

        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $string = $xpath->evaluate("string($expression)");

        if (false !== $string && '' !== $string) {
            return $string;
        }

        return null;
    }
}
