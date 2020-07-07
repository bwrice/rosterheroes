<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeReferenceColumnOnChestBlueprintsToUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chest_blueprints', function (Blueprint $table) {
            $table->dropColumn('reference_id');
            $table->uuid('uuid')->after('id');
        });

        \App\Domain\Models\ChestBlueprint::query()->get()->each(function (\App\Domain\Models\ChestBlueprint $chestBlueprint) {
            $chestBlueprint->uuid = \Illuminate\Support\Str::uuid();
            $chestBlueprint->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chest_blueprints', function (Blueprint $table) {
            //
        });
    }
}
