<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("reference")->unique();
            $table->dateTime("delivered_at")->nullable();
            $table->dateTime("paid_at")->nullable();
            $table->decimal("amount")->nullable();
            $table->boolean("is_draft")->default(true);
            $table->timestamps();
        });

        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id");
            $table->unsignedBigInteger("sellable_id")->nullable();
            $table->string("label");
            $table->integer("quantity");
            $table->float("price");
            $table->float("vat");
            $table->timestamps();

            $table->foreign("order_id")->references("id")->on("orders")->onDelete("cascade");
            $table->foreign("sellable_id")->references("id")->on("sellables")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product');
        Schema::dropIfExists('orders');
    }
}
