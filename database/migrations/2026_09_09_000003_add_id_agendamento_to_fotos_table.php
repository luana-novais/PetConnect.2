<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('fotos', 'id_agendamento')) {
            Schema::table('fotos', function (Blueprint $table) {
                $table->foreignId('id_agendamento')
                    ->nullable()
                    ->after('id_pet')
                    ->constrained('agendamentos')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('fotos', 'id_agendamento')) {
            Schema::table('fotos', function (Blueprint $table) {
                $table->dropForeign(['id_agendamento']);
                $table->dropColumn('id_agendamento');
            });
        }
    }
};