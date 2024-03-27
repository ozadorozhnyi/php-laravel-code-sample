<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        Schema::create('shop_category_localizations', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name', 200);
            $table->string('locale', 2);

            $table->foreign('category_id')->references('id')->on('shop_categories')->onDelete('cascade');
        });
        Schema::create('shop_manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->timestamps();
        });
        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('manufacturer_id');
            $table->string('sku', 100)->unique()->comment('stock keeping unit');
            $table->float('price', 8, 2);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('shop_categories')->onDelete('cascade');
            $table->foreign('manufacturer_id')->references('id')->on('shop_manufacturers')->onDelete('cascade');
        });
        Schema::create('shop_product_localizations', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('name');
            $table->text('description');
            $table->string('color', 100);
            $table->string('locale', 2);

            $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
        });
        Schema::create('shop_product_relations', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('related_product_id');

            $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
            $table->foreign('related_product_id')->references('id')->on('shop_products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_categories');
        Schema::dropIfExists('shop_category_localizations');
        Schema::dropIfExists('shop_manufacturers');
        Schema::dropIfExists('shop_products');
        Schema::dropIfExists('shop_product_localizations');
        Schema::dropIfExists('shop_product_relations');
    }
}
