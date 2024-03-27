<?php

namespace App\Models;

use App\Models\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Object
 * @package App\Models
 * @property int $id
 * @property int $owner_object_id
 * @property int $user_id
 * @property int $type_id
 * @property string $city
 * @property string $region
 * @property string $street
 * @property string $house
 * @property bool $detached_building
 * @property int $number_of_inputs
 * @property string $apartment
 * @property string $floor
 * @property string $entrance
 * @property string $addition
 * @property decimal $latitude
 * @property decimal $longitude
 * @method Builder|$this whereType($type_id)
 */
class ProtectedObject extends Model
{
    use HasFactory, BaseTrait;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'detached_building' => 'boolean',
        'entrance' => 'integer',
        'floor' => 'integer',
        'apartment' => 'integer',
        'latitude' => 'decimal:5',
        'longitude' => 'decimal:5',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'owner_object_id',
        'type_id',
        'city',
        'region',
        'street',
        'house',
        'detached_building',
        'number_of_inputs',
        'apartment',
        'latitude',
        'longitude',
        'floor',
        'entrance',
        'addition',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function owner_object()
    {
        return $this->hasOne(
            OwnerObject::class,
            'owner_object_id',
            'id'
        );
    }

    /**
     * Device (hub), bounded to the current model instance.
     *
     * @return HasOne
     */
    public function device():HasOne
    {
        return $this->hasOne(ProtectedObjectDevice::class);
    }

    /**
     * @param Builder $builder
     * @param $type_id
     */
    public function scopeWhereType(Builder $builder, $type_id)
    {
        $builder->where('type_id', $type_id);
    }

    public function type()
    {
        return $this->belongsTo(
            ProtectedObjectType::class,
            'type_id',
            'id'
        );
    }

    /**
     * Protected Object Owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(
            OwnerObject::class,
            'owner_object_id',
            'id'
        );
    }

    public function scopeWithType(Builder $query)
    {
        return $query->with([
            'type' => fn($query) => $query->withCurrentLocale()
        ]);
    }

    /**
     * The statuses that belong to the protected object.
     */
    public function statuses()
    {
        return $this->belongsToMany(
            ProtectedObjectStatus::class,
            'protected_object_status_log',
            'object_id',
            'status_id'
        )->orderByPivot('id', 'desc')->latest();
    }

    /**
     * @return mixed|null
     */
    public function scopeWithStatusNames(Builder $query)
    {
        return $query->with([
            'statuses' => function($query) {
                $query->withLocale(language()->code)->orderBy('id', 'desc');
            }]
        );
    }
}
