<?php

namespace App\Models\Localizations\Shop;

use Illuminate\Database\Eloquent\Model;

class CategoryLocalization extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shop_category_localizations';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'name', 'locale',
    ];
}
