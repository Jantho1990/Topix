<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopixTopicEloquentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topix_topic_eloquents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email_id');
            $table->string('title');
            $table->longtext('body');
            $table->string('categories');
            $table->string('tags');
            $table->string('date');
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
        Schema::dropIfExists('topix_topic_eloquents');
    }
}
