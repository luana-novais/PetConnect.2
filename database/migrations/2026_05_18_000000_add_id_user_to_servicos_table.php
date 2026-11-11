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
        if (!Schema::hasColumn('servicos', 'id_user')) {
            Schema::table('servicos', function (Blueprint $table) {
                $table->foreignId('id_user')->nullable();
            });
        }

        foreach (Schema::getForeignKeys('servicos') as $foreignKey) {
            if ($foreignKey['columns'] === ['id_user']
                && $foreignKey['foreign_table'] === 'users'
                && $foreignKey['foreign_columns'] === ['id']) {
                return;
            }
        }

        Schema::table('servicos', function (Blueprint $table) {
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('servicos', 'id_user')) {
            return;
        }

        foreach (Schema::getForeignKeys('servicos') as $foreignKey) {
            if ($foreignKey['columns'] === ['id_user']
                && $foreignKey['foreign_table'] === 'users'
                && $foreignKey['foreign_columns'] === ['id']) {
                Schema::table('servicos', function (Blueprint $table) use ($foreignKey) {
                    $table->dropForeign($foreignKey['name']);
                });
            }
        }

        Schema::table('servicos', function (Blueprint $table) {
            $table->dropColumn('id_user');
        });
    }
};
