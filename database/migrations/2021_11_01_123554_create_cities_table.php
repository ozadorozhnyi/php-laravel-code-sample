<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });

        Schema::create('region_localizations', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('region_id');
            $table->string('name', 150);
            $table->string('locale', 2);

            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('region_id');
            $table->string('code', 150)->unique()->comment('used by seeder & grabber');
            $table->timestamps();

            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });

        Schema::create('city_localizations', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('city_id');
            $table->string('name', 150);
            $table->string('locale', 2);

            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('region_localizations');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('city_localizations');
        Schema::dropIfExists('cities');
    }
}
