<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('api', 20);
            $table->string('type', 20);
            $table->text('access_token');
            $table->dateTime('expires_in');
            $table->boolean('used');
            $table->dateTime('date_last_use')->nullable();
            $table->string('url', 255);
            $table->text('payload_send')->nullable();
            $table->text('payload_returned')->nullable();
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
        Schema::dropIfExists('access_tokens');
    }
}
