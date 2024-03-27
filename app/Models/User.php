<?php

namespace App\Models;

use App\Models\Alarm\Alarm;
use App\Models\Shop\ConsultationRequest;
use App\Models\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Payment\Card;

/**
 * Class User
 * @package App\Models
 * @property int $id
 * @property string $email
 * @property string $phone
 * @property string $region
 * @property string $city
 * @property string $password
 * @property int $project_id
 * @property int $type_id
 * @property int $country_id
 * @property int $timezone_id
 * @property int $language_id
 * @property string $first_name
 * @property string $last_name
 * @property string $confirmation_code
 * @property bool $active
 * @method Builder|$this whereType($type_id)
 */
class User extends Authenticatable implements
    HasMedia
{
    use HasFactory,
        BaseTrait,
        HasApiTokens,
        InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'phone',
        'password',
        'project_id',
        'type_id',
        'country_id',
        'timezone_id',
        'first_name',
        'last_name',
        'region',
        'city',
        'active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return string
     */
    public function getModelType(): string
    {
        return static::class;
    }

    /**
     * @return int
     */
    public function getModelId(): int
    {
        return $this->id;
    }

    public function scopeWhereType(Builder $builder, $type_id)
    {
        $builder->where('type_id', $type_id);
    }

    /**
     * @param string $permission
     * @param string|null $guardName
     * @return bool
     */
    public function hasPermissionTo(string $permission, string $guardName = null): bool
    {
        $return = false;
        /**
         * @var Tag $item
         */
        foreach ($this->tags as $item) {
            try {
                if ($return = $item->hasPermissionToParent($permission, $guardName)) {
                    return $return;
                }
            } catch (\Exception $e) {
            }
        }
        return $return;
    }

    public function paymentCard()
    {
        return $this->hasOne(Card::class, 'user_id', 'id');
    }

    public function paymentCards()
    {
        return $this->hasMany(Card::class, 'user_id', 'id');
    }

    public function protectedObjects()
    {
        return $this->hasMany(ProtectedObject::class, 'user_id', 'id');
    }

    /**
     * Consultation requests assigned to the user.
     *
     * @return HasMany
     */
    public function consultationRequests():HasMany
    {
        return $this->hasMany(
            ConsultationRequest::class,
            'user_id',
            'id'
        );
    }

    public function alarms()
    {
        return $this->hasMany(
            Alarm::class,
            'user_id',
            'id'
        );
    }
}
