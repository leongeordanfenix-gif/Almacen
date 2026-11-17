<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('categorias')) {
            Schema::create('categorias', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 80)->unique();
                $table->timestamps();
            });
        }

        Schema::table('productos', function (Blueprint $table) {
            if (!Schema::hasColumn('productos', 'codigo')) {
                $table->string('codigo', 20)->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('productos', 'categoria_id')) {
                $table->foreignId('categoria_id')->nullable()->after('codigo')
                    ->constrained('categorias')->nullOnDelete();
            }
        });

        Schema::table('stock__almacen', function (Blueprint $table) {
            $table->unique(['almacen_id', 'producto_id']);
        });

        Schema::table('movimientos', function (Blueprint $table) {
            if (!Schema::hasColumn('movimientos', 'cantidad')) {
                $table->integer('cantidad')->default(0)->after('tipo_movimiento');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};