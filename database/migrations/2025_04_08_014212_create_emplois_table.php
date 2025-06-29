<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(){
        Schema::create('emplois', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('filiere')->nullable();
            $table->string('niveau')->nullable();
            $table->string('semestre')->nullable();
            $table->string('annee')->nullable();
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('emplois');
    }
};
