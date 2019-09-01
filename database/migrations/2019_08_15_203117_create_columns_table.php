<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('columns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('dashboard_id');
            $table->string('task_ids')->nullable();
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('cascade');
            $table->foreign('account_id')
                ->references('id')->on('accounts')
                ->onDelete('cascade');
            $table->foreign('dashboard_id')
                ->references('id')->on('dashboards')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('columns', function($table) {
            $table->dropForeign(['account_id']);
            $table->dropForeign(['company_id']);
            $table->dropForeign(['dashboard_id']);
        });
        Schema::dropIfExists('columns');
    }
}
