<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('identificacao') || !Schema::hasColumn('identificacao', 'id_foto')) {
            return;
        }

        foreach (Schema::getForeignKeys('identificacao') as $foreignKey) {
            if (in_array('id_foto', $foreignKey['columns'], true)) {
                Schema::table('identificacao', function (Blueprint $table) use ($foreignKey) {
                    $table->dropForeign($foreignKey['name']);
                });
            }
        }

        Schema::table('identificacao', function (Blueprint $table) {
            $table->dropColumn('id_foto');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('identificacao') || Schema::hasColumn('identificacao', 'id_foto')) {
            return;
        }

        Schema::table('identificacao', function (Blueprint $table) {
            $table->foreignId('id_foto')->nullable()->constrained('fotos')->cascadeOnDelete();
        });
    }
};