<?php

require __DIR__.'/../vendor/autoload.php';

$url = 'https://example.bandcamp.com/track/title';
$ripple = new jamband\ripple\Ripple($url);
var_dump($ripple->provider()); // Bandcamp
var_dump($ripple->isValidUrl()); // true

$ripple->request();
var_dump($ripple->id()); // 123456789
var_dump($ripple->title()); // Title, by Artist
var_dump($ripple->image()); // https://img.example.com/img/1234567890.jpg
var_dump($ripple->embed()); // https://bandcamp.com/EmbeddedPlayer/track=123456789/
