<?php

namespace App\Models\Alarm;

use App\Models\Alarm\Status as AlarmStatus;
use App\Models\ProtectedObject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Alarm extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alarms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'protected_object_id',
        'alarm_type_id',
        'alarm_status_id',
        'token',
    ];

    /**
     * Get the user, that owns the Alarm.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the Protected Object, where the Alarm took place.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function protectedObject()
    {
        return $this->belongsTo(ProtectedObject::class, 'protected_object_id', 'id');
    }

    /**
     * Get a type of the Alarm fired.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Type::class, 'alarm_type_id', 'id');
    }

    /**
     * Scope a query to get related model with localization info.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithType(Builder $query)
    {
        return $query->with([
            'type' => fn($builder) => $builder->withCurrentLocale()
        ]);
    }

    /**
     * Get a status of the Alarm fired.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'alarm_status_id', 'id');
    }

    /**
     * The panel signals that belong to the alarm.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function panelSignals()
    {
        return $this->belongsToMany(
            PanelSignalCode::class,
            'alarm_triggered_codes',
            'alarm_id',
            'signal_code_id'
        )->withTimestamps();
    }

    /**
     * Scope a query to only retrieve alarms with panel signals
     * (for the manual type only).
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithPanelSignalCode(Builder $query)
    {
        return $query->with([
            'panelSignals' => function($query){
                $query->withLocale(language()->code)->latest();
        }]);
    }

    /**
     * Scope a query to get related model with localization info.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithStatus(Builder $query)
    {
        return $query->with([
            'status' => fn($builder) => $builder->withLocale(language()->code)
        ]);
    }

    /**
     * Scope a query to only include active alarms.
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where(
            'alarm_status_id',
            AlarmStatus::ofType(AlarmStatus::ACTIVE)->id
        );
    }
}
