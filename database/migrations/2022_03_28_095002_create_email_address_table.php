<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_address', function (Blueprint $table)
	{
            $table->increments('id')->comment = "Table Auto Increment ID";
            $table->integer('contact_id')
                ->index()
                ->comment = "Relationship to Contact";
            $table->string('email_address')
		->index()
		->comment = "Email Address";

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
        Schema::dropIfExists('email_address');
    }
}
