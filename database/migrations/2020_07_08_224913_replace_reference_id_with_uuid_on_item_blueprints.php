<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReplaceReferenceIdWithUuidOnItemBlueprints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_blueprints', function (Blueprint $table) {
            $table->dropColumn('reference_id');
            $table->uuid('uuid')->after('id');
        });

        \App\Domain\Models\ItemBlueprint::query()->get()->each(function (\App\Domain\Models\ItemBlueprint $itemBlueprint) {
            $itemBlueprint->uuid = \Illuminate\Support\Str::uuid();
            $itemBlueprint->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
