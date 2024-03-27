<?php

namespace Database\Seeders\Shop;

use App\Models\Shop\{
    Product as ShopProduct,
    Category as ShopCategory,
    Manufacturer as ProductManufacturer
};
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\{Storage, File};

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->envPrepare();

        $this->getHardcodedListings()->each(function ($listing){
            $category = ShopCategory::create();

            foreach ($listing['locales'] as $locale => $name) {
                $category->locales()->create([
                    'name' => $name,
                    'locale' => $locale
                ]);
            }

            foreach ($listing['products'] as $shop_product) {

                // Create or Update manufacturer
                $manufacturer = ProductManufacturer::firstOrCreate([
                    'name' => $shop_product['manufacturer']
                ]);

                // Create product
                $product = $category->products()->create([
                    'manufacturer_id' => $manufacturer->id,
                    'sku' => $shop_product['sku'],
                    'price' => $shop_product['price'],
                ]);

                // Associating images with conversations
                $product->addMedia($shop_product['photo']['path'].$shop_product['photo']['file_name'])
                    ->preservingOriginal()
                    ->toMediaCollection('photos', 'shop-equipment');

                // Create product localizations
                foreach ($shop_product['locales'] as $locale => $data) {
                    $product->locales()->create([
                        'name' => $data['name'],
                        'description' => $data['description'],
                        'color' => $data['color'],
                        'locale' => $locale,
                    ]);
                }
            }
        });

        // Set product relations
        $this->setProductsRelations();
    }

    /**
     * Get a hardcoded shop products listing from a separate config file.
     * @return Collection
     */
    private function getHardcodedListings():Collection
    {
        return collect(config('seed.shop'));
    }

    /**
     * An abstraction layer over setting products relations.
     * @return void
     */
    private function setProductsRelations():void
    {
        $this->setCategoryBasedRelations();
    }

    /**
     * Set product relations based on joint category.
     * @return void
     */
    private function setCategoryBasedRelations():void
    {
        $products = ShopProduct::select('id', 'category_id')->get();
        if ($products->count()) {
            $products->each(function ($product) {
                $relations = $product->theSameCategoryProducts()->get()->pluck('id');
                if ($relations->count()) {
                    foreach ($relations as $related_product_id) {
                        $product->relatedProducts()->attach($related_product_id);
                    }
                }
            });
        }
    }

    /**
     * Prepare working environment for the database seeding
     * and creating/moving shop equipment photo files.
     *
     * @return void
     */
    private function envPrepare():void
    {
        $this->cleanUpAllEquipmentFiles();
        $this->removeAssociatedSymlinks();
        Artisan::call('storage:link');
    }

    /**
     * Remove all files, shop equipment-related, created previously.
     *
     * @return void
     */
    private function cleanUpAllEquipmentFiles():void
    {
        $directories = Storage::disk(
            config('shop.equipment_photos_media_disk')
        )->allDirectories();

        if (count($directories) > 0) {
            foreach ($directories as $directory) {
                Storage::deleteDirectory($directory);
            }
        }
    }

    /**
     * Remove all symlinks, associated with the equipment-related files.
     *
     * @return void
     */
    private function removeAssociatedSymlinks():void
    {
        $symlink = config('shop.equipment_photos_media_disk');
        if (File::exists(public_path($symlink))) {
            File::delete(public_path($symlink));
        }
    }
}
