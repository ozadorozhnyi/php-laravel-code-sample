<?php

namespace App\Classes\ProtectedObject;

use App\Models\OwnerObject;
use Illuminate\Support\Facades\DB;

/**
 * Class OwnerObjectDirector
 * @package App\Classes\ProtectedObject
 */
class OwnerObjectDirector
{
    /**
     * @var OwnerObjectBuilderInterface
     */
    private $builder;

    /**
     * CalculationBuilder constructor.
     */
    public function __construct(?OwnerObjectBuilderInterface $builder = null)
    {
        $this->builder = $builder ?? new OwnerObjectBuilder();
    }

    /**
     * @param int $created_user_id
     * @param string $first_name
     * @param string $last_name
     * @param string $father_name
     * @param int|null $id_passport
     * @param string|null $passport
     * @param string|null $passport_issued
     * @param string|null $passport_date
     * @param string|null $id_tax
     * @return OwnerObject
     */
    public function createOrGetOwner(
        int $created_user_id,
        string $first_name,
        string $last_name,
        string $father_name,
        int $id_passport = null,
        string $passport = null,
        string $passport_issued = null,
        string $passport_date = null,
        string $id_tax = null
    ): OwnerObject {

        $owner_object = $this->builder
            ->setCreatedUserId($created_user_id)
            ->setFirstName($first_name)
            ->setLastName($last_name)
            ->setFatherName($father_name);

        if ($id_passport) {
            $owner_object->setIdPassport($id_passport);
        } else {
            $owner_object->setPassport($passport);
        }

        $owner_object->setPassportIssued($passport_issued)
            ->setPassportDate($passport_date)
            ->setIdTax($id_tax);

        return $this->createOrGet($owner_object->getOwnerObject());
    }

    /**
     * @param OwnerObject $owner_object
     * @return OwnerObject
     */
    protected function createOrGet(OwnerObject $owner_object): OwnerObject
    {
        return DB::transaction(function () use ($owner_object) {
            $result = null;
            if ($owner_object->id_passport &&
                ($result = OwnerObject::where('created_user_id', $owner_object->created_user_id)->where(
                    'id_passport',
                    $owner_object->id_passport
                )->first())) {
                $owner_object = $result;
            } elseif ($owner_object->passport &&
                ($result = OwnerObject::where('created_user_id', $owner_object->created_user_id)->where(
                    'passport',
                    $owner_object->passport
                )->first())) {
                $owner_object = $result;
            }

            if (!$result) {
                $owner_object->save();
            }

            return $owner_object;
        });
    }
}
