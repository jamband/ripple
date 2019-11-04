# ripple

[![Latest Stable Version](https://poser.pugx.org/jamband/ripple/v/stable)](https://packagist.org/packages/jamband/ripple) [![Total Downloads](https://poser.pugx.org/jamband/ripple/downloads)](https://packagist.org/packages/jamband/ripple) [![Latest Unstable Version](https://poser.pugx.org/jamband/ripple/v/unstable)](https://packagist.org/packages/jamband/ripple) [![License](https://poser.pugx.org/jamband/ripple/license)](https://packagist.org/packages/jamband/ripple) [![Build Status](https://travis-ci.org/jamband/ripple.svg)](https://travis-ci.org/jamband/ripple)

Get a track/album information from the URL.

## Requirements

PHP 7.2 or later

## Installation

```
composer require --prefer-dist jamband/ripple "^0.6"
```

## Usage

```php
// basic
$url = 'https://example.bandcamp.com/track/title';

$ripple = new jamband\ripple\Ripple($url);
var_dump($ripple->provider()); // Bandcamp
var_dump($ripple->isValidUrl()); // true

$ripple->request();
var_dump($ripple->id()); // 123456789
var_dump($ripple->title()); // Title, by Artist
var_dump($ripple->image()); // https://img.example.com/img/1234567890.jpg
```

```php
// embed
$url = 'https://example.bandcamp.com/track/title';

$ripple = new jamband\ripple\Ripple($url);
$ripple->request();

$ripple->setEmbedParams([
    'Bandcamp' => 'size=large/',
]);
$embed = $ripple->embed();
var_dump($embed); // https://bandcamp.com/EmbeddedPlayer/track=123456789/size=large/
?>
<iframe width="300" height="300" src="<?= $embed ?>" frameborder="0" allowfullscreen></iframe>
```

And also check [some samples](https://github.com/jamband/ripple/tree/master/samples).

## Valid URLs

```
Bandcamp:
https?://{subdomain}.bandcamp.com/track/{title}
https?://{subdomain}.bandcamp.com/album/{title}
https?://{domain}/track/{title}
https?://{domain}/album/{title}

SoundCloud:
https?://soundcloud.com/{account}/{title}
https?://soundcloud.com/{account}/sets/{title}

Vimeo:
https?://vimeo.com/{id}

YouTube:
https?://www.youtube.com/watch?v={id}
https?://www.youtube.com/playlist?list={id}
https?://youtu.be/{id}
```

## Supported Providers

- Bandcamp
- SoundCloud
- Vimeo
- YouTube

## License
Ripple is licensed under the MIT license.
