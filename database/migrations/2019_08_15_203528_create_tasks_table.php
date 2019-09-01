<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('description', 1000);
            $table->integer('author_id');
            $table->string('author_name');
            $table->integer('updated_by_id')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->string('assigned_ids')->nullable();
            $table->unsignedBigInteger('column_id');
            $table->boolean('only_assigned_can_see', false);
            $table->timestamps();

            $table->foreign('column_id')
                ->references('id')->on('columns')
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
        Schema::table('tasks', function($table) {
            $table->dropForeign(['column_id']);
        });
        Schema::dropIfExists('tasks');
    }
}
