<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtectedObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owner_objects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('father_name');
            $table->string('passport')
                ->comment('Серия и номер паспорта')->nullable();
            $table->string('passport_issued')
                ->comment('Кем выдан паспорта')->nullable();
            $table->date('passport_date')
                ->comment('Дата выдачи')->nullable();
            $table->unsignedBigInteger('id_tax')
                ->comment('код ИНН')->nullable();
            $table->unsignedBigInteger('id_passport')
                ->comment('ИД паспорта')->nullable();
            $table->timestamps();

            $table->foreign('created_user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->unique(['created_user_id', 'passport']);
            $table->unique(['created_user_id', 'id_passport']);
        });

        Schema::create('protected_object_types', function (Blueprint $table) {
            $table->id();
            $table->string('icon');
            $table->string('icon_ios');
        });

        Schema::create('protected_object_type_localizations', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_id');
            $table->string('locale', 3);
            $table->string('name');

            $table->index(['type_id', 'locale']);

            $table->foreign('type_id')
                ->references('id')
                ->on('protected_object_types')
                ->cascadeOnDelete();
        });

        Schema::create('protected_objects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_object_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('type_id');
            $table->string('city');
            $table->string('region');
            $table->string('street');
            $table->string('house')->comment('номер дома');
            $table->boolean('detached_building')->default(false)->comment('отдельно стоящее здание');
            $table->unsignedTinyInteger('number_of_inputs')->default(1)->comment('кол-во входов');
            $table->string('apartment')->nullable(true)->comment('номер квартиры');
            $table->string('floor')->nullable(true)->comment('Номер этажа');
            $table->string('entrance')->nullable(true)->comment('Номер подъезда');
            $table->string('addition')->nullable()->comment('Дополнительная информация');
            $table->timestamps();

            $table->foreign('owner_object_id')
                ->references('id')
                ->on('owner_objects')
                ->cascadeOnDelete();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->foreign('type_id')
                ->references('id')
                ->on('protected_object_types')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('protected_objects');
        Schema::dropIfExists('protected_object_type_localizations');
        Schema::dropIfExists('protected_object_types');
        Schema::dropIfExists('owner_objects');
    }
}
