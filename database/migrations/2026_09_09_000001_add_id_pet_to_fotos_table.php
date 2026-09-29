<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('fotos', 'id_pet')) {
            Schema::table('fotos', function (Blueprint $table) {
                $table->foreignId('id_pet')
                    ->nullable()
                    ->after('id_user')
                    ->constrained('pets')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('fotos', 'id_pet')) {
            Schema::table('fotos', function (Blueprint $table) {
                $table->dropForeign(['id_pet']);
                $table->dropColumn('id_pet');
            });
        }
    }
};