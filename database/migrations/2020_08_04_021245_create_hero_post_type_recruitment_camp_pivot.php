<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeroPostTypeRecruitmentCampPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hero_post_type_recruitment_camp', function (Blueprint $table) {
            $table->integer('hero_post_type_id')->unsigned();
            $table->integer('recruitment_camp_id')->unsigned();
            $table->primary(['hero_post_type_id', 'recruitment_camp_id'], 'post_type_camp_primary');
            $table->timestamps();
        });

        Schema::table('hero_post_type_recruitment_camp', function (Blueprint $table) {
            $table->foreign('hero_post_type_id')->references('id')->on('hero_post_types');
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
