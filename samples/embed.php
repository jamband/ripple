<?php

require __DIR__.'/../vendor/autoload.php';

$url = 'https://example.bandcamp.com/track/title';

// simple (set url and provider name and ID)
$ripple = new jamband\ripple\Ripple;
var_dump($ripple->embed($url, '12345689')); // https://bandcamp.com/EmbeddedPlayer/track=123456789/

// append parameter
$ripple->setEmbedParams(['Bandcamp' => 'size=large/']);
var_dump($ripple->embed($url, '123456789')); // https://bandcamp.com/EmbeddedPlayer/track=123456789/size=large/

// from track url
$ripple = new jamband\ripple\Ripple($url);
$ripple->request();
var_dump($ripple->embed()); // https://bandcamp.com/EmbeddedPlayer/track=123456789/

// append parameter
$ripple->setEmbedParams([
    'YouTube' => '&autoplay=1',
    'Vimeo' => '&autoplay=1',
    'SoundCloud' => '&auto_play=true&show_comments=false&visual=true',
    'Bandcamp' => 'size=large/',
]);
$embed = $ripple->embed();
var_dump($embed); // https://bandcamp.com/EmbeddedPlayer/track=123456789/size=large/
?>
<!-- embed HTML -->
<iframe width="300" height="300" src="<?= $embed ?>" frameborder="0" allowfullscreen></iframe>
