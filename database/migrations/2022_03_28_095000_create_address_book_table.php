<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_book', function (Blueprint $table)
	{
            $table->increments('id')->comment = "Table Auto Increment ID";
            $table->string('name',64)->comment = "Address Book Name or Group";

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
        Schema::dropIfExists('address_book');
    }
}
