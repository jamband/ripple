# Ripple Change Log

## v0.6.0 - 2019.11.04

- Change: Support PHP version 7.2 or later (BC break)

## v0.5.0 - 2018.07.21

- Change: [#6](https://github.com/jamband/ripple/issues/6): Support PHP version 7.1 or later (BC break)
- Change: [#7](https://github.com/jamband/ripple/issues/7): Change arguments of Ripple::embed() method (BC break)

## v0.4.0 - 2017.03.14

- Change: Support playlists and albums (BC break)

before:
```php
$url = 'https://example.bandcamp.com/track/title';
$ripple = new jamband\ripple\Ripple($url);
$ripple->embed('Bandcamp', '123');
```
after:
```php
$url = 'https://example.bandcamp.com/album/title';
$ripple = new jamband\ripple\Ripple($url);
$ripple->embed($url, 'Bandcamp', '456');　// Argument increased. But Support playlists and albums
```
