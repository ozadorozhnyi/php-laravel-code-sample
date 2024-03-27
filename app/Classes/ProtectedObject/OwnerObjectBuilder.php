<?php

namespace App\Classes\ProtectedObject;

use App\Models\OwnerObject;

/**
 * Class OwnerObjectBuilder
 * @package App\Classes\OwnerObject
 */
class OwnerObjectBuilder implements OwnerObjectBuilderInterface
{
    /**
     * @var OwnerObject|null
     */
    protected ?OwnerObject $ownerObject = null;

    /**
     * OrderBuilder constructor.
     */
    public function __construct(?OwnerObject $ownerObject = null)
    {
        if ($ownerObject) {
            $this->ownerObject = $ownerObject;
        } else {
            $this->reset();
        }
    }

    /**
     * @return OwnerObjectBuilderInterface
     */
    public function reset(): OwnerObjectBuilderInterface
    {
        $this->ownerObject = new OwnerObject();
        return $this;
    }

    /**
     * @param string $father_name
     * @return OwnerObjectBuilderInterface
     */
    public function setFatherName(string $father_name): OwnerObjectBuilderInterface
    {
        $this->ownerObject->father_name = $father_name;
        return $this;
    }

    /**
     * @param string $first_name
     * @return OwnerObjectBuilderInterface
     */
    public function setFirstName(string $first_name): OwnerObjectBuilderInterface
    {
        $this->ownerObject->first_name = $first_name;
        return $this;
    }

    /**
     * @param string $last_name
     * @return OwnerObjectBuilderInterface
     */
    public function setLastName(string $last_name): OwnerObjectBuilderInterface
    {
        $this->ownerObject->last_name = $last_name;
        return $this;
    }

    /**
     * @param int $created_user_id
     * @return OwnerObjectBuilderInterface
     */
    public function setCreatedUserId(int $created_user_id): OwnerObjectBuilderInterface
    {
        $this->ownerObject->created_user_id = $created_user_id;
        return $this;
    }

    /**
     * @param int $id_passport
     * @return OwnerObjectBuilderInterface
     */
    public function setIdPassport(int $id_passport): OwnerObjectBuilderInterface
    {
        $this->ownerObject->id_passport = $id_passport;
        return $this;
    }

    /**
     * @param string $passport
     * @return OwnerObjectBuilderInterface
     */
    public function setPassport(string $passport): OwnerObjectBuilderInterface
    {
        $this->ownerObject->passport = $passport;
        return $this;
    }

    /**
     * @param string $passport_date
     * @return OwnerObjectBuilderInterface
     */
    public function setPassportDate(string $passport_date): OwnerObjectBuilderInterface
    {
        $this->ownerObject->passport_date = $passport_date;
        return $this;
    }

    /**
     * @param string $passport_issued
     * @return OwnerObjectBuilderInterface
     */
    public function setPassportIssued(string $passport_issued): OwnerObjectBuilderInterface
    {
        $this->ownerObject->passport_issued = $passport_issued;
        return $this;
    }

    /**
     * @param string $id_tax
     * @return OwnerObjectBuilderInterface
     */
    public function setIdTax(string $id_tax): OwnerObjectBuilderInterface
    {
        $this->ownerObject->id_tax = $id_tax;
        return $this;
    }

    /**
     * @return OwnerObject|null
     */
    public function getOwnerObject(): OwnerObject
    {
        $protectedObject = $this->ownerObject;
        $this->reset();

        return $protectedObject;
    }
}
