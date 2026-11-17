<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('ruc', 11)->unique();
            $table->string('razon_social', 120);
            $table->string('telefono', 15);
            $table->string('email', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};