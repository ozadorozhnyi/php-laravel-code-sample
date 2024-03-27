<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationRequestProduct extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shop_consultation_requests_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id',
        'product_id',
        'qty'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the consultation request that owns the the consultation request.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consultationRequest():BelongsTo
    {
        return $this->belongsTo(
            ConsultationRequest::class,
            'request_id',
            'id'
        );
    }
}
