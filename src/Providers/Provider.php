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
    protected string|null $response = null;

    /**
     * @param array<string, string|array<int|string, int|string|bool>> $options
     */
    public function __construct(
        protected string $url,
        protected array $options,
    ) {
    }

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

            if (false !== $response) {
                assert(is_string($response));
                $this->response = $response;
            }
        }
    }

    protected function query(string $expression): string|null
    {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;

        if (null !== $this->response && '' !== $this->response) {
            $dom->loadHTML(
                mb_encode_numericentity(
                    $this->response,
                    [0x80, 0x10FFFF, 0, 0x1FFFFF],
                    'UTF-8'
                )
            );
        }

        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $string = $xpath->evaluate("string($expression)");

        if (false !== $string && is_string($string) && '' !== $string) {
            return $string;
        }

        return null;
    }
}
