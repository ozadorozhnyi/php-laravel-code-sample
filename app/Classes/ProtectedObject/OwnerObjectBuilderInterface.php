<?php

namespace App\Classes\ProtectedObject;

use App\Models\OwnerObject;
use Illuminate\Support\Collection;

/**
 * Interface OwnerObjectBuilderInterface
 * @package App\Classes\Order
 */
interface OwnerObjectBuilderInterface
{
    public function reset(): self;

    public function setFirstName(string $first_name): self;

    public function setLastName(string $last_name): self;

    public function setFatherName(string $father_name): self;

    public function setCreatedUserId(int $created_user_id): self;

    public function setIdPassport(int $id_passport): self;

    public function setPassport(string $passport): self;

    public function setPassportIssued(string $passport_issued): self;

    public function setPassportDate(string $passport_date): self;

    public function setIdTax(string $id_tax): self;

    public function getOwnerObject(): OwnerObject;
}
