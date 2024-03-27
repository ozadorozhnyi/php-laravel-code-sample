<?php

namespace App\Repository;

use App\Traits\RepositoryFilter;

class User
{
    use RepositoryFilter;

    /**
     * @var string
     */
    protected $model = \App\Models\User::class;

    /**
     * @param string $email
     * @return $this
     * @throws \Exception
     */
    public function whereEmail(string $email)
    {
        $this->builder()->where('email', $email);
        return $this;
    }

    /**
     * @param $tags
     * @return $this
     * @throws \Exception
     */
    public function whereTags($tags)
    {
        $this->builder()->whereTags($tags);
        return $this;
    }

    /**
     * @param $project_id
     * @return $this
     * @throws \Exception
     */
    public function whereProject($project_id)
    {
        $this->builder()->whereProject($project_id);
        return $this;
    }
}
