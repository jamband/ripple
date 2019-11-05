# ripple

[![Latest Stable Version](https://poser.pugx.org/jamband/ripple/v/stable)](https://packagist.org/packages/jamband/ripple) [![Total Downloads](https://poser.pugx.org/jamband/ripple/downloads)](https://packagist.org/packages/jamband/ripple) [![Latest Unstable Version](https://poser.pugx.org/jamband/ripple/v/unstable)](https://packagist.org/packages/jamband/ripple) [![License](https://poser.pugx.org/jamband/ripple/license)](https://packagist.org/packages/jamband/ripple) [![Build Status](https://travis-ci.org/jamband/ripple.svg)](https://travis-ci.org/jamband/ripple)

Get a track/album information from the URL.

## Requirements

PHP 7.2 or later

## Installation

```
composer require jamband/ripple
```

## Usage

```php
// basic
$ripple = new Jamband\Ripple\Ripple;
$ripple->request('https://example.bandcamp.com/track/title');
$ripple->provider(); // Bandcamp
$ripple->url(); // https://example.bandcamp.com/track/title
$ripple->id(); // 123
$ripple->title(); // Title, by Artist
$ripple->image(); // https://img.example.com/img/123.jpg
```

```php
// embed
$ripple = new Jamband\Ripple\Ripple;
$ripple->options(['embed' => ['Bandcamp' => 'size=large/']]);
$ripple->request('https://example.bandcamp.com/track/title');
$embed = $ripple->embed(); // https://bandcamp.com/EmbeddedPlayer/track=123/size=large/
?>
<iframe width="300" height="300" src="<?= $embed ?>" allowfullscreen></iframe>
```

## Valid URLs

```
Bandcamp:
https://{subdomain}.bandcamp.com/track/{title}
https://{subdomain}.bandcamp.com/album/{title}
https?://{domain}/track/{title}
https?://{domain}/album/{title}

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
