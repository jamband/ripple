<?php

declare(strict_types=1);

return (new PhpCsFixer\Config())->setRules([
    '@PSR12' => true,
])->setFinder(PhpCsFixer\Finder::create()->in([
    __DIR__.'/src',
    __DIR__.'/tests',
]));
