![MusicBrainz](https://raw.githubusercontent.com/metabrainz/metabrainz-logos/master/logos/MusicBrainz/PNG/MusicBrainz_logo_mini.png)

# [MusicBrainz](https://musicbrainz.org/) API Wrapper

MusicBrainz is an open music encyclopedia that collects music metadata and makes it available to the public.

Recommended to use together with [stephan-strate/php-cover-art-archive-api](https://github.com/stephan-strate/php-cover-art-archive-api).

Inspired by [php-github-api](https://github.com/KnpLabs/php-github-api) and [php-tmdb](https://github.com/php-tmdb/api).

## Installation

Using composer:
```
$ composer require stephan-strate/php-music-brainz-api guzzlehttp/guzzle:^7.2 http-interop/http-factory-guzzle:^1.0
```

Why `guzzlehttp/guzzle:^7.2`? This library is decoupled from any http client using [HTTPlug](http://httplug.io/).

## Usage

First you want to create the client:
```php
$client = new \MusicBrainz\Client('MyApplication', '1.1.0', 'contact@example.com');
```

Using this client, you can retrieve all other objects/apis.

### Repository

### Api

The api implementatioon returns the raw json response of the endpoint. You might want to use the repository implementation instead to get the parsed objects.

```php
$release = $client->release();
```

## Contributing

## Help & Donate

I am very curious about projects that use my libraries. Please drop me a short message about what you use the library for. You can find my contact information on my profile (LinkedIn, E-mail).

If this project saved you time and money or you just appreciate what I am doing, please consider sponsoring me 😊
