<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
            $table->boolean('recurring')->default(true)->after('sermon_id');
            $table->timestamp('opens_at')->nullable()->after('image');
            $table->timestamp('closes_at')->nullable()->after('starts_at');

            $table->foreign('sermon_id')->references('id')->on('sermons')->onDelete('set null');
        });

        DB::statement('
            ALTER TABLE broadcasts
                MODIFY day VARCHAR(10) NULL,
                MODIFY time TIME NULL;
        ');
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
            $table->dropForeign(['sermon_id']);
            
            $table->dropColumn('sermon_id');
            $table->dropColumn('recurring');
            $table->dropColumn('opens_at');
            $table->dropColumn('closes_at');
        });

        DB::statement('
            ALTER TABLE broadcasts
                MODIFY day VARCHAR(255) NOT NULL,
                MODIFY time TIME NOT NULL;
        ');
    }
}
