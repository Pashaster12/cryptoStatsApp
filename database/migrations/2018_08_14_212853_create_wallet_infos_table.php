<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('wallet_id')->nullable();
            $table->double('balance')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('wallet_id')
                ->references('id')->on('wallets')
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
        Schema::dropIfExists('wallet_infos');
    }
}
