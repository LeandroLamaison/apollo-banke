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
        Schema::create('external_transfers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('sender_bank_id');
            $table->integer('recipient_bank_id');
            $table->string('sender_card_number');
            $table->string('recipient_card_number');
            $table->float('value');

            $table
                ->foreign('sender_bank_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE');

            $table
                ->foreign('recipient_bank_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('external_transfers');
    }
};
