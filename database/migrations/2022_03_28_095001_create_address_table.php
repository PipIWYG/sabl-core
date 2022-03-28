<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table)
	{
            $table->increments('id')->comment = "Table Auto Increment ID";
            $table->integer('contact_id')
                ->index()
                ->comment = "Relationship to Contact";
            $table->string('street_address_primary')
		->index()
		->comment = "Street Address Line 1";
	    $table->string('street_address_secondary')
		->nullable()
		->index()
                ->comment = "Street Address Line 2";
            $table->string('city')
		->index()
		->comment = "City";

	    // Not indexing or modifying. The ideal approach should map to a countries table instead.
            $table->string('country')
		->comment = "Country";

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
        Schema::dropIfExists('address');
    }
}
