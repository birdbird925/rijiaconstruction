<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialsTable extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('form_id');
            $table->string('form_type');
            $table->text('text');
            $table->integer('quantity');
            $table->string('unit');
            $table->decimal('price', 7, 2);
        });
    }

    public function down()
    {
        Schema::drop('materials');
    }
}
