# Ripple Change Log

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
