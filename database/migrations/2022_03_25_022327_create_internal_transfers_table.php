<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_transfers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('sender_account_id');
            $table->integer('recipient_account_id');
            $table->float('value');

            $table
                ->foreign('sender_account_id')
                ->references('id')
                ->on('accounts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table
                ->foreign('recipient_account_id')
                ->references('id')
                ->on('accounts')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internal_transfers');
    }
};
