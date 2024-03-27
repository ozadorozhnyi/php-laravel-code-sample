<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTypeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_types', function (Blueprint $table) {
            $table->id('id');
        });

        Schema::create('user_type_localizations', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_id');
            $table->string('locale', 3);
            $table->string('name');

            $table->index(['type_id', 'locale']);

            $table->foreign('type_id')
                ->references('id')
                ->on('user_types')
                ->cascadeOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->nullable()->after('email');

            $table->foreign('type_id')
                ->references('id')
                ->on('user_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type_id');
        });
        Schema::drop("user_types");
    }
}
