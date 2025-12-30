<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users');

            $table->string('name', 255);
            $table->string('brand_name', 255)->nullable();
            $table->string('description', 255);
            $table->unsignedInteger('price');
            $table->string('condition', 50);
            $table->string('image_path', 255);

            $table->string('shipping_postal_code', 8)->nullable();
            $table->string('shipping_address', 255)->nullable();
            $table->string('shipping_building', 255)->nullable();

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
        Schema::dropIfExists('items');
    }
}
