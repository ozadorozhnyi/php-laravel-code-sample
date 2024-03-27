<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_consultation_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('partner_id');
            $table->unsignedBigInteger('partner_city_id');
            $table->string('comment')->nullable(true);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('partner_id')
                ->references('id')
                ->on('partners')
                ->onDelete('cascade');

            $table->foreign('partner_city_id')
                ->references('id')
                ->on('cities')
                ->onDelete('cascade');
        });

        Schema::create('shop_consultation_requests_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedTinyInteger('qty')->default(1);

            $table->foreign('request_id')
                ->references('id')
                ->on('shop_consultation_requests')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('shop_products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * Maintain the order of the tables to properly perform the rollback operation.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_consultation_requests_products');
        Schema::dropIfExists('shop_consultation_requests');
    }
}
