<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGeoFieldsToTheProtectedObjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('protected_objects', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)
                ->after('addition')
                ->comment('широта: 90 до -90 градусов');

            $table->decimal('longitude', 11, 8)
                ->after('latitude')
                ->comment('долгота: 180 до -180 градусов');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('protected_objects', function (Blueprint $table) {
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
}
