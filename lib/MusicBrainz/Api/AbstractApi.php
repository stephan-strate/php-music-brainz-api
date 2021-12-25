<?php

/**
 * This file is part of the MusicBrainz API Wrapper created by Stephan Strate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package MusicBrainz\Api
 * @author Stephan Strate <hello@stephan.codes>
 * @link https://github.com/stephan-strate/php-music-brainz-api
 * @copyright (c) 2021, Stephan Strate
 * @version 0.0.1
 */

namespace MusicBrainz\Api;

use MusicBrainz\HttpClient\Message\ResponseMediator;
use Http\Client\Exception;
use Http\Message\Exception\UnexpectedValueException;
use MusicBrainz\Client;

/**
 * Basic api implementation to use in other apis.
 * @package MusicBrainz\Api
 */
abstract class AbstractApi
{
    /**
     * The http client instance.
     * @var Client
     */
    protected Client $client;

    /**
     * Create new api instance.
     * Make http client available to the api instance.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send a GET request with query parameters.
     * @param string                $path           request path
     * @param array<string, string> $parameters     query parameters (appended to request path)
     * @param array<string, string> $requestHeaders request headers
     * @return mixed|string associative array, when valid json response
     *                      or raw response as string
     */
    public function get(string $path, array $parameters = [], array $requestHeaders = [])
    {
        // build query parameters and append to request path
        if (count($parameters) > 0) {
            $path .= '?' . http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);
        }

        try {
            $response = $this->client
                ->getHttpClient()
                ->get($path, $requestHeaders);
            return ResponseMediator::getContent($response);
        } catch (Exception $e) {
            throw new UnexpectedValueException('Request failed', 723497812);
        }
    }
}
