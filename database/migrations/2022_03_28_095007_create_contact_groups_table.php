<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the Phone Number Database Table
        Schema::create('contact_groups', function (Blueprint $table)
        {
            $table->integer('contact_id')
                ->unsigned()
                ->index();
            $table->integer('groups_id')
                ->unsigned()
                ->index('group_contact_group_id_foreign');
            $table->primary(['contact_id','groups_id']);

            $table->foreign('groups_id')
                ->references('id')
                ->on('groups')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('contact_id')
                ->references('id')
                ->on('contact')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_groups');
    }
}
