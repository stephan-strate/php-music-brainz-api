<?php

/**
 * This file is part of the MusicBrainz API Wrapper created by Stephan Strate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package MusicBrainz\Api\NonCore
 * @author Stephan Strate <hello@stephan.codes>
 * @link https://github.com/stephan-strate/php-music-brainz-api
 * @copyright (c) 2021, Stephan Strate
 * @version 0.0.1
 */

namespace MusicBrainz\Api\NonCore;

use MusicBrainz\Api\DefaultApi;

/**
 * Specific api implementation for collection.
 * @package MusicBrainz\Api\NonCore
 */
class Collection extends DefaultApi
{
    protected string $entityType = 'collection';
}
