<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtectedObjectDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protected_object_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('protected_object_id');
            $table->string('hub_id')->unique()->nullable(false)->comment('Идентификатор хаба или его серийный номер – это первые 8 символов под QR кодом хаба.');
            $table->string('manufacturer')->nullable(false)->comment('Название производителя. Латиницей, без пробелов, в нижнем регистре.');
            $table->timestamps();

            $table->foreign('protected_object_id')
                ->references('id')
                ->on('protected_objects')
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
        Schema::dropIfExists('protected_object_devices');
    }
}
