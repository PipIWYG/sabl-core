<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSablTablesAddFxKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Disable foreign key constraints to add foreign keys
        Schema::disableForeignKeyConstraints();

        // Alter Consultants
        Schema::table('contact', function(Blueprint $table)
        {
            // Add foreign keys
            $table->foreign('ab_id')
                ->references('id')
                ->on('address_book');
        });

        // Alter Consultants
        Schema::table('address', function(Blueprint $table)
        {
            // Add foreign keys
            $table->foreign('contact_id')
                ->references('id')
                ->on('contact');
        });

        // Alter Consultants
        Schema::table('email_address', function(Blueprint $table)
        {
            // Add foreign keys
            $table->foreign('contact_id')
                ->references('id')
                ->on('contact');
        });

        // Alter Consultants
        Schema::table('phone_number', function(Blueprint $table)
        {
            // Add foreign keys
            $table->foreign('contact_id')
                ->references('id')
                ->on('contact');
        });

        
        // Enable foreign key constraints to add foreign keys
        Schema::disableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
