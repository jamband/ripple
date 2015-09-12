<?php

/*
 * This file is part of the ripple library.
 *
 * (c) Tomoki Morita <tmsongbooks215@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace jamband\ripple\tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Middleware;

trait ClientTrait
{
    /**
     * @see ClientTest::getGuzzle()
     * https://github.com/FriendsOfPHP/Goutte/blob/master/Goutte/Tests/ClientTest.php
     */
    protected function getGuzzle($response)
    {
        $handlerStack = HandlerStack::create(
            new MockHandler([new GuzzleResponse(200, [], $response)])
        );
        $history = [];
        $handlerStack->push(Middleware::history($history));

        $guzzle = new GuzzleClient([
            'redirect.disable' => true,
            'base_uri' => '',
            'handler' => $handlerStack,
        ]);

        return $guzzle;
    }
}
