<?php

namespace MusicBrainz;

use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\HttpClient;
use Http\Discovery\UriFactoryDiscovery;
use MusicBrainz\Api\Core\Artist;
use MusicBrainz\HttpClient\Builder;

/**
 * Class Client
 * @method Artist artist()
 * @package MusicBrainz
 */
class Client
{
    /**
     *
     */
    const BASE_URI = 'https://musicbrainz.org/ws/2/';

    /**
     * @var Builder
     */
    private $httpClientBuilder;

    public function __construct(Builder $httpClientBuilder = null)
    {
        $this->httpClientBuilder = $httpClientBuilder ?: new Builder();

        $this->httpClientBuilder->addPlugin(new AddHostPlugin(UriFactoryDiscovery::find()->createUri(self::BASE_URI)));
        //$this->httpClientBuilder->addPlugin(new RedirectPlugin());
        $this->httpClientBuilder->addHeaders([
            'Accept' => 'application/json',
        ]);
    }

    public function api($name)
    {
        switch ($name) {
            case 'artist':
                return new Artist($this);
            default:
                throw new \InvalidArgumentException(sprintf('Undefined api instance called "%s"', $name));
        }
    }

    public function getHttpClient()
    {
        return $this->httpClientBuilder->getHttpClient();
    }

    public function getHttpClientBuilder()
    {
        return $this->httpClientBuilder;
    }

    public static function createWithHttpClient(HttpClient $httpClient)
    {
        $builder = new Builder($httpClient);
        return new self($builder);
    }
}