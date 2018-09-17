<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Config;

class CreateActionTrackerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Config::get('action-tracker.table_name'), function (Blueprint $table) {
            $table->increments('id');
            $table->string(Config::get('action-tracker.prefix').'_type');
            $table->integer(Config::get('action-tracker.prefix').'_id');
            $table->integer('user_id')->nullable();
            $table->string('action');
            $table->text('message')->nullable();
            $table->text('extra')->nullable();
            $table->text('values')->nullable();
            $table->timestamps();

            $table->index(['action_tracker_id', 'action_tracker_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(Config::get('action-tracker.table_name'));
    }
}
