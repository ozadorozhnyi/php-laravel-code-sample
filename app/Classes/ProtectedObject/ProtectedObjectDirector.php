<?php

namespace App\Classes\ProtectedObject;

use App\Models\ProtectedObject;
use Illuminate\Support\Facades\DB;
use App\Classes\GeoCoordinates;

/**
 * Class ProtectedObjectDirector
 * @package App\Classes\ProtectedObject
 */
class ProtectedObjectDirector
{
    /**
     * @var ProtectedObjectBuilderInterface
     */
    private $builder;

    /**
     * ProtectedObjectBuilder constructor.
     */
    public function __construct(?ProtectedObjectBuilderInterface $builder = null)
    {
        $this->builder = $builder ?? new ProtectedObjectBuilder();
    }

    /**
     * Create a resource.
     *
     * @param int $user_id
     * @param int $owner_object_id
     * @param int $type_id
     * @param string $city
     * @param string $region
     * @param string $street
     * @param string $house
     * @param string $detached_building
     * @param string $number_of_inputs
     * @param string|null $apartment
     * @param string|null $floor
     * @param string|null $entrance
     * @param string|null $addition
     * @return ProtectedObject
     */
    public function userCreate(
        int $user_id,
        int $owner_object_id,
        int $type_id,
        string $city,
        string $region,
        string $street,
        string $house,
        string $detached_building,
        string $number_of_inputs,
        ?string $apartment,
        ?string $floor,
        ?string $entrance,
        ?string $addition
    ): ProtectedObject {

        $geo_coordinates = GeoCoordinates::get(
            sprintf("%s, %s, %s %s", $region, $city, $street, $house)
        );

        $protected_object = $this->builder
            ->setUserId($user_id)
            ->setOwnerObject($owner_object_id)
            ->setTypeId($type_id)
            ->setCity($city)
            ->setRegion($region)
            ->setStreet($street)
            ->setHouse($house)
            ->setDetachedBuilding($detached_building)
            ->setNumberOfInputs($number_of_inputs)
            ->setApartment($apartment)
            ->setFloor($floor)
            ->setEntrance($entrance)
            ->setAddition($addition)
            ->setLatitude($geo_coordinates['latitude'])
            ->setLongitude($geo_coordinates['longitude'])
            ->getProtectedObject();

        return $this->save($protected_object);
    }

    /**
     * Update an existed resource.
     *
     * @param int $id
     * @param string $city
     * @param string $region
     * @param string $street
     * @param string $house
     * @param string $detached_building
     * @param string $number_of_inputs
     * @param string|null $apartment
     * @param string|null $floor
     * @param string|null $entrance
     * @param string|null $addition
     * @return ProtectedObject
     */
    public function objectUpdate(
        int $id,
        string $city,
        string $region,
        string $street,
        string $house,
        string $detached_building,
        string $number_of_inputs,
        ?string $apartment,
        ?string $floor,
        ?string $entrance,
        ?string $addition
    ): ProtectedObject {
        $builder = $this->builder->setProtectedObject(
            ProtectedObject::find($id)
        );

        $this->builder
            ->setCity($city)
            ->setRegion($region)
            ->setStreet($street)
            ->setHouse($house)
            ->setDetachedBuilding($detached_building)
            ->setNumberOfInputs($number_of_inputs);

        if ($apartment !== null)
            $this->builder->setApartment($apartment);

        if ($floor !== null)
            $this->builder->setFloor($floor);

        if ($entrance !== null)
            $this->builder->setEntrance($entrance);

        if ($addition !== null)
            $this->builder->setAddition($addition);

        return $this->save(
            $builder->getProtectedObject()
        );
    }

    /**
     * @param ProtectedObject $protected_object
     * @return ProtectedObject
     */
    protected function save(ProtectedObject $protected_object): ProtectedObject
    {
        DB::transaction(function () use ($protected_object) {
            $protected_object->save();
        });

        return $protected_object;
    }

}
