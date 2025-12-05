# ripple

[![Build Status](https://github.com/jamband/ripple/workflows/ci/badge.svg)](https://github.com/jamband/ripple/actions?workflow=ci) [![Latest Stable Version](https://img.shields.io/packagist/v/jamband/ripple)](https://packagist.org/packages/jamband/ripple) [![Total Downloads](https://img.shields.io/packagist/dt/jamband/ripple)](https://packagist.org/packages/jamband/ripple)

Get a track/album information from the URL.

## Requirements

PHP 8.2 or later

## Installation

```
composer require jamband/ripple
```

## Usage

```php
// basic
$ripple = new Jamband\Ripple\Ripple();
$ripple->request('https://example.bandcamp.com/track/title');
$ripple->provider(); // Bandcamp
$ripple->url(); // https://example.bandcamp.com/track/title
$ripple->id(); // 123
$ripple->title(); // Title, by Artist
$ripple->image(); // https://img.example.com/img/123.jpg
```

```php
// embed
$ripple = new Jamband\Ripple\Ripple();
$ripple->options(['embed' => ['Bandcamp' => 'size=large/']]);
$ripple->request('https://example.bandcamp.com/track/title');
$embed = $ripple->embed(); // https://bandcamp.com/EmbeddedPlayer/track=123/size=large/
?>
<iframe width="300" height="300" src="<?= $embed ?>" allow="fullscreen"></iframe>
```

```php
// custom curl options
$ripple = new Jamband\Ripple\Ripple();
$ripple->options(['curl' => [
    // CURLOPT_TIMEOUT => 8,
    // CURLOPT_USERAGENT => '...',
    // ...
]]);
$ripple->request('https://example.bandcamp.com/track/title');
```

```php
// mock response
$ripple = new Jamband\Ripple\Ripple();
$ripple->options(['response' => '...']);
$ripple->request('https://example.bandcamp.com/track/title');
```

## Valid URLs

```
Bandcamp:
https://{subdomain}.bandcamp.com/track/{title}
https://{subdomain}.bandcamp.com/album/{title}
https://{subdomain}.bandcamp.com/releases
https?://{domain}/track/{title}
https?://{domain}/album/{title}
https?://{domain}/releases

SoundCloud:
https://soundcloud.com/{account}/{title}
https://soundcloud.com/{account}/sets/{title}

Vimeo:
https://vimeo.com/{id}

YouTube:
https://www.youtube.com/watch?v={id}
https://www.youtube.com/playlist?list={id}
https://youtu.be/{id}
```

## Supported Providers

- Bandcamp
- SoundCloud
- Vimeo
- YouTube

## License
Ripple is licensed under the MIT license.
