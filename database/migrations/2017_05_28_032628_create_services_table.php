<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('form_id');
            $table->string('form_type');
            $table->text('text');
            $table->decimal('price', 7, 2);
        });
    }

    public function down()
    {
        Schema::drop('services');
    }
}
