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
    public function url(): string;

    public function id(): string|null;

    public function title(): string|null;

    public function image(): string|null;

    public function embed(string|null $url = null, string|null $id = null): string|null;
}
