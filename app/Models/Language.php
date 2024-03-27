<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BaseTrait;

class Language extends Model
{
    use BaseTrait;

    /**
     * @var bool
     */
    public $timestamps = false;
}
