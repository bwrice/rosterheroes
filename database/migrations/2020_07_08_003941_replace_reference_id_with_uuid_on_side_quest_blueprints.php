<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReplaceReferenceIdWithUuidOnSideQuestBlueprints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('side_quest_blueprints', function (Blueprint $table) {
            $table->dropColumn('reference_id');
            $table->uuid('uuid')->after('id');
        });

        \App\Domain\Models\SideQuestBlueprint::query()->get()->each(function (\App\Domain\Models\SideQuestBlueprint $sideQuestBlueprint) {
            $sideQuestBlueprint->uuid = \Illuminate\Support\Str::uuid();
            $sideQuestBlueprint->save();
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
