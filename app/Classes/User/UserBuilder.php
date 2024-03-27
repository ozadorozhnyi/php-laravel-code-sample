<?php

namespace App\Classes\User;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserBuilder implements UserBuilderInterface
{
    /**
     * @var ?User
     */
    private $user = null;

    /**
     * OrderBuilder constructor.
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * @return UserBuilderInterface
     */
    public function reset(): UserBuilderInterface
    {
        $this->user = new User();
        return $this;
    }

    /**
     * @param int|null $project_id
     * @return UserBuilderInterface
     */
    public function setProject(?int $project_id = null): UserBuilderInterface
    {
        $this->user->project_id = $project_id;
        return $this;
    }

    /**
     * @param int $type_id
     * @return UserBuilderInterface
     */
    public function setType(int $type_id): UserBuilderInterface
    {
        $this->user->type_id = $type_id;
        return $this;
    }

    /**
     * @param string $email
     * @return UserBuilderInterface
     */
    public function setEmail(string $email): UserBuilderInterface
    {
        $this->user->email = $email;
        return $this;
    }

    /**
     * @param string $password
     * @return UserBuilderInterface
     */
    public function setPassword(string $password): UserBuilderInterface
    {
        $this->user->password = Hash::make($password);
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        $result = $this->user;
        $this->reset();

        return $result;
    }

    protected function init()
    {
        $this->user->password = Hash::make($validate['password']);
    }
}
