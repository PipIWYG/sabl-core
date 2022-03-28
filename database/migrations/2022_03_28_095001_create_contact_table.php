<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact', function (Blueprint $table)
	{
            $table->increments('id')->comment = "Table Auto Increment ID";
            $table->integer('ab_id')
                ->index()
                ->comment = "Relationship on Address Book / Group";
            $table->string('first_name',64)
		->index()
		->comment = "First Name";
            $table->string('last_name',64)
		->index()
		->comment = "Last Name";

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
        Schema::dropIfExists('contact');
    }
}
