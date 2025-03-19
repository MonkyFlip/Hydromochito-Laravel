<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_registros_iot', function (Blueprint $table) {
            $table->increments('id_registro');
            $table->decimal('flujo_agua', 8, 2);
            $table->decimal('nivel_agua', 8, 2);
            $table->decimal('temp', 5, 2);
            $table->enum('energia', ['solar', 'electricidad']);
            $table->bigInteger('id_usuario');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_registros_iot');
    }
};
