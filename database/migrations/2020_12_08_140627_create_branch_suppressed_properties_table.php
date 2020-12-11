<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchSuppressedPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_suppressed_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->index();
            $table->string('address');
            $table->string('postcode');
            $table->bigInteger('uprn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_suppressed_properties');
    }
}
