<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_district', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id');
            $table->foreignId('district_id');
            $table->timestamps();

            $table->unique(['branch_id', 'district_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_district', function (Blueprint $table) {
            //
        });
    }
}
