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
        // Create the Phone Number Database Table
        Schema::create('phone_number', function (Blueprint $table)
        {
            $table->increments('id')
                ->comment = "Table Auto Increment ID";

            // Relationship Index to Contact
            $table->integer('contact_id')
                ->unsigned()
                ->index()
                ->comment = "Relationship to Contact";

            // Phone Number Specific Data Fields
            $table->string('phone_number')
		        ->index()
		        ->comment = "Phone Number";

	        // Add Table Timestamps, including deleted_at for soft-deletes
            $table->timestamp('created_at')
                ->index();
            $table->timestamp('updated_at')
                ->nullable()
                ->index();
            $table->timestamp('deleted_at')
                ->nullable()
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
