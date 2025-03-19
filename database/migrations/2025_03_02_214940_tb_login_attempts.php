<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_login_attempts', function (Blueprint $table){
            $table->id();
            $table->string('email');
            $table->integer('attempts')->default(0);
            $table->timestamp('lockout_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_login_attempts');
    }
};
