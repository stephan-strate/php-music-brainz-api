<?php

/**
 * This file is part of the MusicBrainz API Wrapper created by Stephan Strate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package MusicBrainz\HttpClient\Message
 * @author Stephan Strate <hello@stephan.codes>
 * @link https://github.com/stephan-strate/php-music-brainz-api
 * @copyright (c) 2021, Stephan Strate
 * @version 0.0.1
 */

namespace MusicBrainz\HttpClient\Message;

use Psr\Http\Message\ResponseInterface;

/**
 * Utility class for response specific operations.
 * @package MusicBrainz\HttpClient\Message
 */
final class ResponseMediator
{
    /**
     * Extract and parse response body if content type is json.
     * @param ResponseInterface $response   response to extract body from
     * @return mixed|string extracted body and maybe parsed as array
     */
    public static function getContent(ResponseInterface $response)
    {
        $body = $response->getBody()->__toString();
        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') === 0) {
            $content = json_decode($body, true);
            if (JSON_ERROR_NONE === json_last_error()) {
                return $content;
            }
        }

        return $body;
    }
}
