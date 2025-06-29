<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(){
        Schema::create('seances', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('emploi_id');
            $table->foreign('emploi_id')->references('id')->on('emplois')->onDelete('cascade');
            $table->string('jour');
            $table->enum('creneau', ['08h00 à 10h00', '10h15 à 12h15', '14h00 à 16h00', '16h15 à 18h15']);
            $table->string('matiere')->nullable();
            $table->json('nature_ens')->nullable(); // multiple options: ["Cours", "TP"]
            $table->string('professeur')->nullable();
            $table->string('semaine_range')->nullable();
            $table->integer('nombre_semaines')->nullable();
            $table->integer('duree')->nullable();
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('seances');
    }
};
