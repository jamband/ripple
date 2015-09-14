# ripple

[![Latest Stable Version](https://poser.pugx.org/jamband/ripple/v/stable)](https://packagist.org/packages/jamband/ripple) [![Total Downloads](https://poser.pugx.org/jamband/ripple/downloads)](https://packagist.org/packages/jamband/ripple) [![Latest Unstable Version](https://poser.pugx.org/jamband/ripple/v/unstable)](https://packagist.org/packages/jamband/ripple) [![License](https://poser.pugx.org/jamband/ripple/license)](https://packagist.org/packages/jamband/ripple) [![Build Status](https://travis-ci.org/jamband/ripple.svg)](https://travis-ci.org/jamband/ripple)

Get track information from the URL.

## Requirements

This library depends on PHP 5.6+ and [Goutte](https://github.com/FriendsOfPHP/Goutte) 3+. And using [oEmbed](http://oembed.com/).

## Installation

```
php composer.phar require --prefer-dist jamband/ripple "*"
```

or add in composer.json
```
"jamband/ripple": "*"
```

## Usage

```php
<?php
// basic
$url = 'http://linneshelvete.bandcamp.com/track/tjeresten';

$ripple = new \jamband\ripple\Ripple($url);
var_dump($ripple->url); // http://linneshelvete.bandcamp.com/track/tjeresten
var_dump($ripple->provider); // Bandcamp
var_dump($ripple->isValidUrl()); // true

$ripple->request(new \Goutte\Client());
var_dump($ripple->id()); // 932292198
var_dump($ripple->title()); // Tjeresten, by Linnés Helvete
var_dump($ripple->image()); // http://f1.bcbits.com/img/a3144407673_16.jpg
```

And also check [some samples](https://github.com/jamband/ripple/tree/master/samples).

```php
<?php
// embed
$url = 'http://linneshelvete.bandcamp.com/track/tjeresten';

$ripple = new \jamband\ripple\Ripple($url);
$ripple->request(new \Goutte\Client());

$ripple->setEmbedParams([
    'Bandcamp' => 'size=large/',
]);
$embed = $ripple->embed($ripple->provider, $ripple->id());
var_dump($embed); // https://bandcamp.com/EmbeddedPlayer/track=932292198/size=large/
?>
<iframe width="300" height="300" src="<?= $embed ?>" frameborder="0" allowfullscreen></iframe>
```

## Supported Providers

- Bandcamp
- SoundCloud
- Vimeo
- YouTube

## License
Ripple is licensed under the MIT license.
