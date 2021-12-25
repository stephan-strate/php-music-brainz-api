<?php

/**
 * This file is part of the MusicBrainz API Wrapper created by Stephan Strate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package MusicBrainz
 * @author Stephan Strate <hello@stephan.codes>
 * @link https://github.com/stephan-strate/php-music-brainz-api
 * @copyright (c) 2021, Stephan Strate
 * @version 0.0.1
 */

namespace MusicBrainz;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Discovery\Psr17FactoryDiscovery;
use MusicBrainz\Api\AbstractApi;
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
     * MusicBrainz base uri.
     * @link https://musicbrainz.org/doc/MusicBrainz_API
     */
    const BASE_URI = 'https://musicbrainz.org/ws/2/';

    /**
     * Customized http client with plugins.
     * @var Builder
     */
    private Builder $httpClientBuilder;

    /**
     * Create api client with default builder or provide base builder.
     * MusicBrainz requires the caller to provide information about the application to
     * apply proper rate limiting.
     * @param string $name      application name (eg. MyAwesomeTagger)
     * @param string $version   application version (eg. 1.2.0)
     * @param string $contact   contact information, email, website (eg. me@example.com)
     * @param Builder|null $httpClientBuilder
     * @see https://musicbrainz.org/doc/MusicBrainz_API/Rate_Limiting
     */
    public function __construct(string $name, string $version, string $contact, Builder $httpClientBuilder = null)
    {
        $this->httpClientBuilder = $httpClientBuilder ?: new Builder();

        $uriInterface = Psr17FactoryDiscovery::findUriFactory()->createUri(self::BASE_URI);
        $this->httpClientBuilder->addPlugin(new AddHostPlugin($uriInterface));

        $this->httpClientBuilder->addHeaders([
            'Accept' => 'application/json',
            'Application' => $name . '/' . $version . '(' . $contact . ')',
        ]);
    }

    /**
     * Get api instance by name.
     * @param string $name  api name
     * @return AbstractApi  api instance
     */
    public function api(string $name): AbstractApi
    {
        switch ($name) {
            case 'artist':
                return new Artist($this);
            default:
                throw new \InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name));
        }
    }

    /**
     * Publish api instances as implicit class functions.
     * @param string $name  api name
     * @param mixed  $args
     * @return AbstractApi  api instance
     */
    public function __call(string $name, $args): AbstractApi
    {
        return $this->api($name);
    }

    /**
     * Get the configured http client to perform requests.
     * @return HttpMethodsClientInterface
     */
    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->httpClientBuilder->getHttpClient();
    }
}
