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
}
