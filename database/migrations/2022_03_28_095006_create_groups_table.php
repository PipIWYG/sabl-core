<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the Phone Number Database Table
        Schema::create('groups', function (Blueprint $table)
        {
            $table->increments('id')
                ->comment = "Table Auto Increment ID";

            // Phone Number Specific Data Fields
            $table->string('name')
		        ->comment = "Group Name";

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
        Schema::dropIfExists('groups');
    }
}
