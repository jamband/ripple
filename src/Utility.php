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

use DomDocument;
use DomXPath;
use DomNodeList;

/**
 * Utility trait file.
 */
trait Utility
{
    /**
     * Returns the domain name from URL.
     * @return null|string
     */
    private static function getDomain($url)
    {
        $domain = parse_url($url, PHP_URL_HOST);

        if (null !== $domain) {
            return implode('.', array_slice(explode('.', $domain), -2));
        }
        return null;
    }

    /**
     * @param string $url
     * @param array $options
     * @return null|string
     */
    private static function http($url, array $options = [])
    {
        $options = array_replace([
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_ENCODING => 'gzip',
            CURLOPT_FAILONERROR => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Jamband/Ripple',
        ], $options);

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        if (false !== $response) {
            return $response;
        }
        return null;
    }

    /**
     * @param string $content
     * @param string $expression
     * @return null|string
     */
    private static function query($content, $expression)
    {
        if (null !== $content) {
            libxml_use_internal_errors(true);
            $dom = new DomDocument;
            $dom->loadHtml(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
            libxml_clear_errors();
            return (new DomXPath($dom))->evaluate($expression) ?: null;
        }
        return null;
    }
}
