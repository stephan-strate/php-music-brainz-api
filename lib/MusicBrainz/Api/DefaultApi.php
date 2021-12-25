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

/**
 * MusicBrainz offers different endpoints with similar parameters
 * and options. They all differ from the requested entity type.
 * This is the default implementation for all endpoints.
 * @package MusicBrainz\Api
 * @see https://musicbrainz.org/doc/MusicBrainz_API
 */
abstract class DefaultApi extends AbstractApi
{
    /**
     * Requested entity type.
     * @var string
     */
    protected string $entityType;

    /**
     * Fetch specific entity using the MusicBrainz identifier.
     * @param string $mbid      MusicBrainz identifier
     * @param array $includes   include more information about the entity
     * @return mixed|string
     * @see https://musicbrainz.org/doc/MusicBrainz_API#Subqueries
     */
    public function lookup(string $mbid, array $includes = array())
    {
        return $this->get($this->entityType . '/' . $mbid . '?inc=' . self::includeParameter($includes));
    }

    /**
     * Fetch specific amount of entities directly linked to another entity. For example find 200 releases of an artist.
     * @param string $browsingEntityType    filter entity type (eg. artist)
     * @param string $mbid                  MusicBrainz identifier of filter entity type
     * @param int $limit                    amount of entities to fetch (api limit is 100, more will be splitted
     *                                      into multiple request automatically)
     * @param int $offset                   typical offset for pagination
     * @param array $includes               include more information about the entity
     * @return array
     */
    public function browse(string $browsingEntityType, string $mbid, int $limit = 100, int $offset = 0, array $includes = array()): array
    {
        $entities = [];

        do {
            $result = $this->get($this->entityType . '?' . $browsingEntityType . '=' . $mbid . '&limit='.
                min(100, $limit) . '&offset=' . $offset . '&inc=' . self::includeParameter($includes));
            $entities = array_merge($entities, $result[$this->entityType . 's']);

            // handle limit or offset overflow by limiting it to the total count of entities
            $totalCount = $result[$this->entityType . '-count'];
            if ($limit > ($totalCount - $offset)) {
                $limit = $totalCount - $offset;
            }

            // handle regular request
            $offset += 100;
            $limit = max(0, $limit - 100);
        } while ($limit > 0);

        return $entities;
    }

    /**
     * Fetch all entities directly linked to another entity. For example find all releases of an artist.
     * @param string $browsingEntityType    filter entity type (eg. artist)
     * @param string $mbid                  MusicBrainz identifier of filter entity type
     * @param int $offset                   typical offset for pagination
     * @param array $includes               include more information about the entity
     * @return array
     */
    public function browseAll(string $browsingEntityType, string $mbid, int $offset = 0, array $includes = array()): array
    {
        return $this->browse($browsingEntityType, $mbid, PHP_INT_MAX, $offset, $includes);
    }

    /**
     * @param string $query
     * @param int $limit
     * @param int $offset
     * @return mixed|string
     */
    public function search(string $query, int $limit = 100, int $offset = 0)
    {
        return $this->get($this->entityType . '?query=' . urlencode($query) . '&limit=' . $limit .
            '&offset=' . $offset);
    }

    protected static function includeParameter($includes): string
    {
        return implode('+', $includes);
    }
}
