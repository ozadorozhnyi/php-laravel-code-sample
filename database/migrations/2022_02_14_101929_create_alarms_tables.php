<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlarmsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alarm_panel_signals', function(Blueprint $table) {
            $table->id();
            $table->unsignedInteger('object_id')->comment('внутренний идентиф. объекта УОС');
            $table->unsignedInteger('event_code')->comment('код события');
            $table->char('event_type', 1)->comment('тип события: E-событие, R-восстановление');
            $table->timestamps();
        });
        Schema::create('alarm_panel_signal_codes', function(Blueprint $table){
            $table->id();
            $table->unsignedInteger('code');
            $table->string('slug');
            $table->boolean('trigger_alarm')->default(false)->comment('вызывать тревогу по этому коду?');
            $table->timestamps();
        });
        Schema::create('alarm_panel_signal_code_localization', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('signal_code_id');
            $table->string('name');
            $table->string('description');
            $table->char('locale', 2);
            $table->foreign('signal_code_id')
                ->references('id')
                ->on('alarm_panel_signal_codes')
                ->onUpdate('cascade')
                ->onDelele('cascade');
        });
        Schema::create('alarm_statuses', function(Blueprint $table){
            $table->id();
            $table->unsignedInteger('code');
            $table->string('slug');
            $table->timestamps();
        });
        Schema::create('alarm_status_localization', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('alarm_status_id');
            $table->string('name');
            $table->string('description');
            $table->char('locale', 2);
            $table->foreign('alarm_status_id')
                ->references('id')
                ->on('alarm_statuses')
                ->onUpdate('cascade')
                ->onDelele('cascade');
        });
        Schema::create('alarm_types', function(Blueprint $table){
            $table->id();
            $table->string('slug');
            $table->timestamps();
        });
        Schema::create('alarm_type_localization', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('alarm_type_id');
            $table->string('name');
            $table->string('description');
            $table->char('locale', 2);
            $table->foreign('alarm_type_id')
                ->references('id')
                ->on('alarm_types')
                ->onUpdate('cascade')
                ->onDelele('cascade');
        });
        Schema::create('alarms', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('protected_object_id')->comment('FK on PK объекта');
            $table->unsignedBigInteger('alarm_type_id')->comment('FK');
            $table->unsignedBigInteger('alarm_status_id')->comment('FK');
            $table->string('token')->nullable(false)->comment('токен для отмены тревоги');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->foreign('protected_object_id')
                ->references('id')
                ->on('protected_objects')
                ->onUpdate('cascade')
                ->onDelele('cascade');
            $table->foreign('alarm_type_id')
                ->references('id')
                ->on('alarm_types')
                ->onUpdate('cascade')
                ->onDelele('cascade');
            $table->foreign('alarm_status_id')
                ->references('id')
                ->on('alarm_statuses')
                ->onUpdate('cascade')
                ->onDelele('cascade');
        });
        Schema::create('alarm_triggered_codes', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('alarm_id');
            $table->unsignedBigInteger('signal_code_id');
            $table->timestamps();
            $table->foreign('alarm_id')
                ->references('id')
                ->on('alarms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('signal_code_id')
                ->references('id')
                ->on('alarm_panel_signal_codes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::table('protected_objects', function(Blueprint $table) {
            $table->unsignedInteger('uos_object_id')
                ->nullable(true)
                ->comment('номер объекта, заполняется на стороне пульта охраны')
                ->after('owner_object_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alarm_triggered_codes');
        Schema::dropIfExists('alarms');
        Schema::dropIfExists('alarm_panel_signals');
        Schema::dropIfExists('alarm_panel_signal_code_localization');
        Schema::dropIfExists('alarm_panel_signal_codes');
        Schema::dropIfExists('alarm_status_localization');
        Schema::dropIfExists('alarm_statuses');
        Schema::dropIfExists('alarm_type_localization');
        Schema::dropIfExists('alarm_types');
        Schema::table('protected_objects', function (Blueprint $table) {
            $table->dropColumn('uos_object_id');
        });
    }
}
