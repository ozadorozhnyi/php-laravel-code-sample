<?php

namespace App\Classes\User;

use App\Models\User;

interface UserBuilderInterface
{
    public function reset(): UserBuilderInterface;

    public function setProject(?int $project_id = null): UserBuilderInterface;

    public function setType(int $type_id): UserBuilderInterface;

    public function setEmail(string $email): UserBuilderInterface;

    public function setPassword(string $password): UserBuilderInterface;

    public function getUser(): User;
}
