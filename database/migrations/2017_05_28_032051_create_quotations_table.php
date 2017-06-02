<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date');
            $table->string('to');
            $table->string('title');
            $table->tinyInteger('status');
        });
    }

    public function down()
    {
        Schema::drop('quotations');
    }
}
