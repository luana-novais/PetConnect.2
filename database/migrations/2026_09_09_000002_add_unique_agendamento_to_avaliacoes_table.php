<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $hasDuplicates = DB::table('avaliacoes')
            ->select('id_agendamento')
            ->groupBy('id_agendamento')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($hasDuplicates && DB::connection()->getDriverName() === 'mysql') {
            DB::statement('DELETE primeira FROM avaliacoes primeira INNER JOIN avaliacoes duplicada ON primeira.id_agendamento = duplicada.id_agendamento AND primeira.id > duplicada.id');
        } elseif ($hasDuplicates) {
            DB::table('avaliacoes')
                ->whereIn('id', function ($query) {
                    $query->select('duplicada.id')
                        ->from('avaliacoes as duplicada')
                        ->join('avaliacoes as primeira', 'primeira.id_agendamento', '=', 'duplicada.id_agendamento')
                        ->whereColumn('duplicada.id', '>', 'primeira.id');
                })
                ->delete();
        }

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
