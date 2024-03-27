<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerApplication extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'partner_applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'partner_id',
        'protected_object_id',
        'region_id',
        'comment'
    ];

    /**
     * Set Relation between Equipment Requests and Protected Object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function protectedObject()
    {
        return $this->belongsTo(
            ProtectedObject::class,
            'protected_object_id',
            'id'
        );
    }
}
