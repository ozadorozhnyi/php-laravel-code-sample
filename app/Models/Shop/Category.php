<?php

namespace App\Models\Shop;

use App\Models\LocalizationModel;
use App\Models\Localizations\Shop\CategoryLocalization;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends LocalizationModel
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shop_categories';

    protected $localeModel = CategoryLocalization::class;

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
