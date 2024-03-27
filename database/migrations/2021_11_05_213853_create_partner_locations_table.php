<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id');
            $table->unsignedBigInteger('city_id');
            $table->timestamps();

            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });

        Schema::create('partner_locations_localizations', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('partner_location_id');
            $table->string('street', 200);
            $table->string('locale', 2);

            $table->foreign('partner_location_id')->references('id')->on('partner_locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partner_locations');
        Schema::dropIfExists('partner_locations_localizations');
    }
}
