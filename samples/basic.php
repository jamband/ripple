<?php

require __DIR__.'/../vendor/autoload.php';

use jamband\ripple\Ripple;
use Goutte\Client;

// $url = 'https://folkadelphia.bandcamp.com/track/facing-west';
// $url = 'https://soundcloud.com/the-staves/pay-us-no-mind';
// $url = 'https://vimeo.com/67320034';
$url = 'https://www.youtube.com/watch?v=MBlpfXLQLvU';

$ripple = new Ripple($url);
var_dump($ripple->url); // https://www.youtube.com/watch?v=MBlpfXLQLvU
var_dump($ripple->provider); // YouTube
var_dump($ripple->isValidUrl()); // true

$ripple->request(new Client());
var_dump($ripple->id()); // MBlpfXLQLvU
var_dump($ripple->title()); // The Staves - The Motherlode (Official Video)
var_dump($ripple->image()); // https://i.ytimg.com/vi/MBlpfXLQLvU/hqdefault.jpg
var_dump($ripple->embed($ripple->provider, $ripple->id())); // https://www.youtube.com/embed/MBlpfXLQLvU
