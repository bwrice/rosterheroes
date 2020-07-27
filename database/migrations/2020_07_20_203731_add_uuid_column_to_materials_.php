<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUuidColumnToMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->uuid('uuid')->after('id');
        });

        \App\Domain\Models\Material::query()->each(function (\App\Domain\Models\Material $material) {
            $material->uuid = (string) \Illuminate\Support\Str::uuid();
            $material->save();
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
