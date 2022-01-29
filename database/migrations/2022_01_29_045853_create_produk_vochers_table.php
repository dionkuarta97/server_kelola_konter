<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukVochersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk_vochers', function (Blueprint $table) {
            $table->id('produkVocherId');
            $table->string('nama');
            $table->integer('vocherId');
            $table->integer('harga');
            $table->integer('modal');
            $table->integer('stock');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk_vochers');
    }
}
