<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtectedObjectStatusesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protected_object_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->unsignedSmallInteger('code');
            $table->char('color' ,7)->default('#999999');
            $table->timestamps();
        });

        Schema::create('protected_object_status_localizations', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('status_id');
            $table->string('name');
            $table->string('description');
            $table->string('locale', 2);

            $table->foreign('status_id')
                ->references('id')
                ->on('protected_object_statuses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('protected_object_status_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('object_id');
            $table->unsignedBigInteger('status_id');
            $table->timestamp('created_at', $precision = 0);

            $table->foreign('status_id')
                ->references('id')
                ->on('protected_object_statuses')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('object_id')
                ->references('id')
                ->on('protected_objects')
                ->onUpdate('cascade')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('protected_object_status_log');
        Schema::dropIfExists('protected_object_status_localizations');
        Schema::dropIfExists('protected_object_statuses');
    }
}
