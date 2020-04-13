<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellables', function (Blueprint $table) {
            $table->id();
            $table->string("label");
            $table->decimal("price");
            $table->unsignedBigInteger("vat_id");
            $table->unsignedBigInteger("sellable_id");
            $table->unsignedBigInteger("category_id")->nullable();
            $table->string("sellable_type");
            $table->timestamps();

            $table->foreign("category_id")->references("id")->on("categories")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sellables');
    }
}
