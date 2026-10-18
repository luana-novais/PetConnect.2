<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('agendamentos') && !Schema::hasColumn('agendamentos', 'id_pet')) {
            Schema::table('agendamentos', function (Blueprint $table) {
                $table->foreignId('id_pet')
                    ->nullable()
                    ->constrained('pets')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('agendamentos', 'id_pet')) {
            Schema::table('agendamentos', function (Blueprint $table) {
                $table->dropConstrainedForeignId('id_pet');
            });
        }
    }
};