<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('name',200);
            $table->string('thumbnail',200);
            $table->text('content',300);
            $table->text('description',2000);
            $table->string('creator',50)->nullable();
            $table->string('repairer',50)->nullable();
            $table->string('disabler',50)->nullable();
            $table->unsignedBigInteger('postcat_id');
            $table->foreign('postcat_id')->references('id')->on('postcats')->onDelete('cascade');//Thiết lập khóa ngoại cho bảng xóa du lieu ca 2 bang
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
        Schema::dropIfExists('posts');
    }
}
