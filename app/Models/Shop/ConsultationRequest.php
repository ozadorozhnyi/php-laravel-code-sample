<?php

namespace App\Models\Shop;

use App\Models\User;
use App\Models\Partner;
use App\Models\PartnerLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationRequest extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shop_consultation_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'partner_id',
        'partner_city_id',
        'comment'
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    public function partner():BelongsTo
    {
        return $this->belongsTo(
            Partner::class,
            'partner_id',
            'id'
        );
    }

    public function partnerCity()
    {
        return $this->belongsTo(
            PartnerLocation::class,
            'partner_city_id',
            'city_id'
        );
    }

    public function products()
    {
        return $this->hasOne(
            ConsultationRequestProduct::class,
            'request_id', 'id'
        );
    }

}
