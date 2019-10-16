<?php

use App\Domain\Models\MeasurableType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedTitans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $titans = [
            [
                'name' => 'Skeleton Overlord',
                'level' => 127,
            ],
            [
                'name' => 'Skeleton Nobel',
                'level' => 91,
            ],
        ];
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
