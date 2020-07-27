<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUuidColumnToItemTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_types', function (Blueprint $table) {
            $table->uuid('uuid')->after('id');
        });

        \App\Domain\Models\ItemType::query()->get()->each(function (\App\Domain\Models\ItemType $itemType) {
            $itemType->uuid = (string) \Illuminate\Support\Str::uuid();
            $itemType->save();
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
