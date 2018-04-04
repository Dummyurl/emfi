<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFiledHomeSlidersTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_sliders', function (Blueprint $table) {
            $table->string('option_maturity')->after('order')->nullable();
            $table->string('option_price')->after('option_maturity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_sliders', function (Blueprint $table) {
            //
        });
    }
}
