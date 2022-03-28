<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_number', function (Blueprint $table) {
            $table->increments('id')->comment = "Table Auto Increment ID";
            $table->integer('contact_id')
                ->index()
                ->comment = "Relationship to Contact";
            $table->string('phone_number')
		->index()
		->comment = "Phone Number";

	    // Add Table Timestamps, including deleted_at for soft-deletes
            $table->timestamp('created_at')
                ->index();
            $table->timestamp('updated_at')
                ->index();
            $table->timestamp('deleted_at')
                ->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phone_number');
    }
}
