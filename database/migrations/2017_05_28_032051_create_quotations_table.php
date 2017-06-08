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
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('email')->nullable();
            $table->string('tel')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('material_included')->default(1);
            $table->tinyInteger('status');
        });
    }

    public function down()
    {
        Schema::drop('quotations');
    }
}
