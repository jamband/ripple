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

interface ProviderInterface
{
    /**
     * @return string
     */
    public static function validUrlPattern(): string;

    /**
     * @param string $content
     * @return null|string
     */
    public static function id(string $content): ?string;

    /**
     * @param string $content
     * @return null|string
     */
    public static function title(string $content): ?string;

    /**
     * @param string $content
     * @return null|string
     */
    public static function image(string $content): ?string;

    /**
     * @param string $id
     * @param bool $hasMultiple
     * @return null|string
     */
    public static function embed(string $id, bool $hasMultiple): ?string;
}
