<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSermonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sermons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->string('youtube_id')->unique()->nullable();;
            $table->string('vimeo_id')->unique()->nullable();;
            $table->integer('speaker_id')->unsigned()->nullable();;
            $table->integer('series_id')->unsigned()->nullable();
            $table->integer('duration')->default(0);
            $table->string('image')->nullable();
            $table->timestamp('publish_on')->nullable();
            $table->timestamps();

            $table->foreign('speaker_id')->references('id')->on('speakers');
            $table->foreign('series_id')->references('id')->on('series');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sermons');
    }
}
