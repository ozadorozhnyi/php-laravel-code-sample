<?php

namespace App\Classes;

/**
 * Class GeoCoordinates
 * @package App\Classes\ProtectedObject
 *
 * @todo via application service providers.
 */
class GeoCoordinates
{
    /**
     * Abstraction layer to make it easier
     * to change the driver without changing the interface.
     *
     * @param string $address
     * @return \Illuminate\Support\Collection|mixed
     */
    public static function get(string $address)
    {
        return self::loadSampleCoordinates();
    }

    /**
     * Get a random geo-location coordinates.
     *
     * @return \Illuminate\Support\Collection|mixed
     */
    private static function loadSampleCoordinates()
    {
        return collect(
            config('seed.geo_coordinate_examples')
        )->random();
    }
}
