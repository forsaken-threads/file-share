<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('name');
            $table->string('original_name');
            $table->string('share_name');
            $table->string('mime_type');
            $table->unsignedInteger('size');
            $table->unsignedTinyInteger('visibility');
            $table->unsignedInteger('downloads')->default(0);
            $table->string('password')->nullable();
            $table->dateTime('expiration')->nullable();
            $table->unsignedInteger('max_downloads')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->unique('name');
        });

        Schema::create('visibilities', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name');
        });

        foreach (['Only Me', 'Public without Password', 'Public with Password', 'Any Authenticated User'] as $visibility) {
            $v = new \App\Visibility();
            $v->name = $visibility;
            $v->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
        Schema::dropIfExists('visibilities');
    }
}
