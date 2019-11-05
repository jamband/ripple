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

interface ProviderInterface
{
    /**
     * @return string
     */
    public function url(): string;

    /**
     * @return string|null
     */
    public function id(): ?string;

    /**
     * @return string|null
     */
    public function title(): ?string;

    /**
     * @return string|null
     */
    public function image(): ?string;

    /**
     * @param string|null $url
     * @param string|null $id
     * @return string|null
     */
    public function embed(?string $url = null, ?string $id = null): ?string;
}
