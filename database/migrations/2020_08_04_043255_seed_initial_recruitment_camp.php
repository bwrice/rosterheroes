<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedInitialRecruitmentCamp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $province = \App\Domain\Models\Province::query()->where('name', '=', 'Febrijan')->first();

        /** @var \App\Domain\Models\RecruitmentCamp $camp */
        $camp = \App\Domain\Models\RecruitmentCamp::query()->create([
            'name' => 'Febrijan Recruitment Camp',
            'uuid' => \Illuminate\Support\Str::uuid(),
            'province_id' => $province->id,
        ]);

        $camp->heroClasses()->saveMany(\App\Domain\Models\HeroClass::all());
        $camp->heroPostTypes()->saveMany(\App\Domain\Models\HeroPostType::all());
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
