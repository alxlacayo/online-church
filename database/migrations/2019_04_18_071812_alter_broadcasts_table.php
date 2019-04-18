<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBroadcastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('broadcasts', function($table)
        {
            $table->integer('sermon_id')->unsigned()->nullable()->after('enabled');
            $table->timestamp('opens_at')->nullable()->after('image');
            $table->timestamp('closes_at')->nullable()->after('starts_at');

            $table->foreign('sermon_id')->references('id')->on('sermons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('broadcasts', function($table)
        {
            $table->dropForeign('broadcasts_sermon_id_foreign');
            
            $table->dropColumn('sermon_id');
            $table->dropColumn('opens_at');
            $table->dropColumn('closes_at');
        });
    }
}
