<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUuidsToEnchantmentsAndSpells extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enchantments', function (Blueprint $table) {
            $table->uuid('uuid')->after('id');
        });

        \App\Domain\Models\Enchantment::query()->each(function (\App\Domain\Models\Enchantment $enchantment) {
            $enchantment->uuid = (string) \Illuminate\Support\Str::uuid();
            $enchantment->save();
        });

        Schema::table('spells', function (Blueprint $table) {
            $table->uuid('uuid')->after('id');
        });

        \App\Domain\Models\Spell::query()->each(function (\App\Domain\Models\Spell $spell) {
            $spell->uuid = (string) \Illuminate\Support\Str::uuid();
            $spell->save();
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
