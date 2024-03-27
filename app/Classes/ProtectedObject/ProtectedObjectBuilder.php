<?php

namespace App\Classes\ProtectedObject;

use App\Models\ProtectedObject;

/**
 * Class ProtectedObjectBuilder
 * @package App\Classes\ProtectedObject
 */
class ProtectedObjectBuilder implements ProtectedObjectBuilderInterface
{
    /**
     * @var ProtectedObject|null
     */
    protected ?ProtectedObject $protectedObject = null;


    /**
     * OrderBuilder constructor.
     */
    public function __construct(?ProtectedObject $protectedObject = null)
    {
        if ($protectedObject) {
            $this->setProtectedObject($protectedObject);
        } else {
            $this->reset();
        }
    }

    /**
     * @return ProtectedObjectBuilderInterface
     */
    public function reset(): ProtectedObjectBuilderInterface
    {
        $this->protectedObject = new ProtectedObject();
        return $this;
    }

    public function setProtectedObject(?ProtectedObject $protectedObject = null)
    {
        $this->protectedObject = $protectedObject;
        return $this;
    }

    public function setAddition(?string $addition): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->addition = $addition;
        return $this;
    }

    public function setApartment(string $apartment): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->apartment = $apartment;
        return $this;
    }

    public function setLatitude(float $latitude): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->latitude = $latitude;
        return $this;
    }

    public function setLongitude(float $longitude): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->longitude = $longitude;
        return $this;
    }

    public function setCity(string $city): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->city = $city;
        return $this;
    }

    public function setEntrance(string $entrance): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->entrance = $entrance;
        return $this;
    }

    public function setFloor(string $floor): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->floor = $floor;
        return $this;
    }

    public function setHouse(string $house): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->house = $house;
        return $this;
    }

    public function setDetachedBuilding(bool $detached_building): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->detached_building = $detached_building;
        return $this;
    }

    public function setNumberOfInputs(int $number_of_inputs): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->number_of_inputs = $number_of_inputs;
        return $this;
    }

    public function setOwnerObject(int $owner_object_id): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->owner_object_id = $owner_object_id;
        return $this;
    }

    public function setUosObjectId(int $uos_object_id): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->uos_object_id = $uos_object_id;
        return $this;
    }

    public function setRegion(string $region): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->region = $region;
        return $this;
    }

    public function setStreet(string $street): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->street = $street;
        return $this;
    }

    public function setTypeId(int $type_id): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->type_id = $type_id;
        return $this;
    }

    public function setUserId(int $user_id): ProtectedObjectBuilderInterface
    {
        $this->protectedObject->user_id = $user_id;
        return $this;
    }

    /**
     * @return ProtectedObject|null
     */
    public function getProtectedObject(): ProtectedObject
    {
        $protectedObject = $this->protectedObject;
        $this->reset();

        return $protectedObject;
    }
}
