<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->primary('id');
            $table->unsignedInteger('id');
            $table->string('title');
            $table->string('set');
            $table->text('types');
            $table->text('brigades');
            $table->string('strength');
            $table->string('toughness');
            $table->string('class');
            $table->text('special_ability');
            $table->text('identifiers');
            $table->string('reference');
            $table->string('artist');
            $table->string('rarity');
            $table->text('play_as');
            $table->text('errata');
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
        Schema::dropIfExists('cards');
    }
}
