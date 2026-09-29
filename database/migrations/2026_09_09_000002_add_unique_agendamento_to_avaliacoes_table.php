<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DELETE primeira FROM avaliacoes primeira INNER JOIN avaliacoes duplicada ON primeira.id_agendamento = duplicada.id_agendamento AND primeira.id > duplicada.id');

        Schema::table('avaliacoes', function (Blueprint $table) {
            $table->unique('id_agendamento', 'avaliacoes_agendamento_unique');
        });
    }

    public function down(): void
    {
        Schema::table('avaliacoes', function (Blueprint $table) {
            $table->dropUnique('avaliacoes_agendamento_unique');
        });
    }
};
