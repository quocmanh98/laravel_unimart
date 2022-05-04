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
            $table->string('masp');
            $table->string('thumbnail');
            $table->string('name');
            $table->unsignedBigInteger('price');
            $table->bigInteger('qty');
            $table->string('color',50);
            $table->unsignedBigInteger('subtotal');
            $table->string('payment');
            $table->string('status',50);
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');//Thiết lập khóa ngoại cho bảng xóa du lieu ca 2 bang
            $table->string('MaKH',50);
            $table->string('disabler',50)->nullable();
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
        Schema::dropIfExists('orders');
    }
}
