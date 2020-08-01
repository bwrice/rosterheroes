<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailSubscriptionUserPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_subscription_user', function (Blueprint $table) {
            $table->integer('email_subscription_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->primary(['email_subscription_id', 'user_id']);
            $table->timestamps();
        });

        Schema::table('email_subscription_user', function (Blueprint $table) {
            $table->foreign('email_subscription_id')->references('id')->on('email_subscriptions');
            $table->foreign('user_id')->references('id')->on('users');
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
