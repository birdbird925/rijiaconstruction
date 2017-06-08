<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quotation_id')->nullable()->unsigned();
            $table->string('to');
            $table->string('company_line_1')->nullable();
            $table->string('company_line_2')->nullable();
            $table->string('purchase_order')->nullable();
            $table->decimal('deposit', 7, 2)->nullable();
            $table->tinyInteger('material_included')->default(1);
            $table->timestamp('date');

            $table->foreign('quotation_id')
                  ->references('id')->on('quotations')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('invoices');
    }
}
