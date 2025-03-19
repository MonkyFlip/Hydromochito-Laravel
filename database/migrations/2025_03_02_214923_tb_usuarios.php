<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_usuarios', function(Blueprint $blueprint){
            $blueprint->increments('id_usuario');
            $blueprint->string('nombre', 50);
            $blueprint->string('email', 50)->unique();
            $blueprint->string('password', 255); // Aumentado a 255 caracteres
            $blueprint->integer('id_rol')->unsigned();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_usuarios');
    }
};

