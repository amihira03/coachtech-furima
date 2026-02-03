<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyConditionIdToNotNullableOnItemsTable extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['condition_id']);
        });

        DB::statement('ALTER TABLE items MODIFY condition_id BIGINT UNSIGNED NOT NULL');

        Schema::table('items', function (Blueprint $table) {
            $table->foreign('condition_id')
                ->references('id')
                ->on('conditions')
                ->onDelete('RESTRICT');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['condition_id']);
        });

        DB::statement('ALTER TABLE items MODIFY condition_id BIGINT UNSIGNED NULL');

        Schema::table('items', function (Blueprint $table) {
            $table->foreign('condition_id')
                ->references('id')
                ->on('conditions')
                ->onDelete('SET NULL');
        });
    }
}
