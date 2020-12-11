<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_bundles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('number_of_credits')->default(0);
            $table->bigInteger('cost')->default(0);
            $table->date('valid_form')->nullable();
            $table->date('valid_to')->nullable();
            $table->boolean('active')->default(false);
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
        Schema::dropIfExists('credit_bundles');
    }
}
