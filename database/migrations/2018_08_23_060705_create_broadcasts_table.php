<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBroadcastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broadcasts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('day');
            $table->time('time');
            $table->text('description')->nullable();
            $table->boolean('live')->default(false);
            $table->boolean('enabled')->default(true);
            $table->text('embed_code')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('broadcasts');
    }
}
