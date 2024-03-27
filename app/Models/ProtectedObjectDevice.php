<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProtectedObjectDevice extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'protected_object_devices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'protected_object_id',
        'hub_id',
        'manufacturer'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'hub_id';
    }

    /**
     * Accessor!
     *
     * Get device name in snake case notation.
     *
     * @param  string  $value
     * @return string
     */
    public function getManufacturerAttribute($value)
    {
        return Str::snake($value);
    }

    /**
     * Set the device name in snake case notation
     *
     * @param  string  $value
     * @return void
     */
    public function setManufacturerAttribute($value)
    {
        $this->attributes['manufacturer'] = Str::snake($value);
    }

    /**
     * The Protected Object to which the device is bound.
     *
     * @return BelongsTo
     */
    public function protectedObject():BelongsTo
    {
        return $this->belongsTo(ProtectedObject::class, 'protected_object_id', 'id');
    }
}
