<?php

namespace App\Models;

use App\Models\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class User
 * @package App\Models
 * @property int $id
 * @property string $phone
 * @property string $token
 * @property string $code
 * @property int $verified
 */
class UserPhoneVerify extends Model
{
    use HasFactory,
        BaseTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone',
        'token',
        'code',
        'verified'
    ];
}
