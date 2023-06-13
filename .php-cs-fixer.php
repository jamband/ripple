<?php

declare(strict_types=1);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'declare_strict_types' => true,
    ])->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
    );
