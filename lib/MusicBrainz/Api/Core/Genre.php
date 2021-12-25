<?php

/**
 * This file is part of the MusicBrainz API Wrapper created by Stephan Strate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package MusicBrainz\Api\Core
 * @author Stephan Strate <hello@stephan.codes>
 * @link https://github.com/stephan-strate/php-music-brainz-api
 * @copyright (c) 2021, Stephan Strate
 * @version 0.0.1
 */

namespace MusicBrainz\Api\Core;

use MusicBrainz\Api\DefaultApi;
use MusicBrainz\Exception\NotImplementedException;

/**
 * Specific api implementation for genre.
 * @package MusicBrainz\Api\Core
 */
class Genre extends DefaultApi
{
    protected string $entityType = 'genre';

    public function browse(string $browsingEntityType, string $mbid, int $limit = 100, int $offset = 0, array $includes = array()): array
    {
        throw new NotImplementedException('MusicBrainz does not support browse requests for genre entity.');
    }

    public function search(string $query, int $limit = 100, int $offset = 0)
    {
        throw new NotImplementedException('MusicBrainz does not support search requests for genre entity.');
    }
}
