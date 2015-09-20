<?php

require __DIR__.'/../vendor/autoload.php';

use jamband\ripple\Ripple;
use Goutte\Client;

// $url = 'https://folkadelphia.bandcamp.com/track/facing-west';
$url = 'https://soundcloud.com/the-staves/pay-us-no-mind';
// $url = 'https://vimeo.com/67320034';
// $url = 'https://www.youtube.com/watch?v=MBlpfXLQLvU';

// simple (set provider name and ID)
$ripple = new Ripple();
var_dump($ripple->embed('YouTube', 'MBlpfXLQLvU')); // https://www.youtube.com/embed/MBlpfXLQLvU

// append parameter
$ripple->setEmbedParams(['YouTube' => '?autoplay=1']);
var_dump($ripple->embed('YouTube', 'MBlpfXLQLvU')); // https://www.youtube.com/embed/MBlpfXLQLvU?autoplay=1

// from track url
$ripple = new Ripple($url);
$ripple->request(new Client());
var_dump($ripple->embed()); // https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/42854561

// append parameter
$ripple->setEmbedParams([
    'YouTube' => '?autoplay=1',
    'Vimeo' => '?autoplay=1',
    'SoundCloud' => '?auto_play=true&amp;show_comments=false&amp;visual=true',
    'Bandcamp' => 'size=large/',
]);
$embed = $ripple->embed();
var_dump($embed); // https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/42854561?auto_play=true&amp;show_comments=false&amp;visual=true
?>
<!-- embed HTML -->
<iframe width="300" height="300" src="<?= $embed ?>" frameborder="0" allowfullscreen></iframe>
