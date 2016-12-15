<?php

require __DIR__.'/../vendor/autoload.php';

// $url = 'https://folkadelphia.bandcamp.com/track/facing-west';
// $url = 'https://soundcloud.com/the-staves/pay-us-no-mind';
// $url = 'https://vimeo.com/67320034';
$url = 'https://www.youtube.com/watch?v=MBlpfXLQLvU';

$ripple = new jamband\ripple\Ripple($url);
var_dump($ripple->provider()); // YouTube
var_dump($ripple->isValidUrl()); // true

$ripple->request();
var_dump($ripple->id()); // MBlpfXLQLvU
var_dump($ripple->title()); // The Staves - The Motherlode (Official Video)
var_dump($ripple->image()); // https://i.ytimg.com/vi/MBlpfXLQLvU/hqdefault.jpg
var_dump($ripple->embed()); // https://www.youtube.com/embed/MBlpfXLQLvU
