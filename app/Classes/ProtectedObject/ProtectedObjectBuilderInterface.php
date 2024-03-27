<?php

namespace App\Classes\ProtectedObject;

use App\Models\ProtectedObject;

interface ProtectedObjectBuilderInterface
{
    public function reset(): ProtectedObjectBuilderInterface;

    public function setUserId(int $user_id): ProtectedObjectBuilderInterface;
    public function setTypeId(int $type_id): ProtectedObjectBuilderInterface;
    public function setCity(string $city): ProtectedObjectBuilderInterface;
    public function setRegion(string $region): ProtectedObjectBuilderInterface;
    public function setStreet(string $street): ProtectedObjectBuilderInterface;
    public function setHouse(string $house): ProtectedObjectBuilderInterface;
    public function setDetachedBuilding(bool $detached_building): ProtectedObjectBuilderInterface;
    public function setNumberOfInputs(int $number_of_inputs): ProtectedObjectBuilderInterface;
    public function setApartment(string $apartment): ProtectedObjectBuilderInterface;
    public function setFloor(string $floor): ProtectedObjectBuilderInterface;
    public function setEntrance(string $entrance): ProtectedObjectBuilderInterface;
    public function setAddition(?string $addition): ProtectedObjectBuilderInterface;
    public function setLatitude(float $addition): ProtectedObjectBuilderInterface;
    public function setLongitude(float $addition): ProtectedObjectBuilderInterface;
    public function setOwnerObject(int $owner_object_id): ProtectedObjectBuilderInterface;
    public function setUosObjectId(int $uos_object_id): ProtectedObjectBuilderInterface;

    public function getProtectedObject(): ProtectedObject;
}
