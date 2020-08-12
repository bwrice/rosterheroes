<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeroClassRecruitmentCampPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hero_class_recruitment_camp', function (Blueprint $table) {
            $table->integer('hero_class_id')->unsigned();
            $table->integer('recruitment_camp_id')->unsigned();
            $table->primary(['hero_class_id', 'recruitment_camp_id'], 'hero_class_camp_primary');
            $table->timestamps();
        });

        Schema::table('hero_class_recruitment_camp', function (Blueprint $table) {
            $table->foreign('hero_class_id')->references('id')->on('hero_classes');
            $table->foreign('recruitment_camp_id')->references('id')->on('recruitment_camps');
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
