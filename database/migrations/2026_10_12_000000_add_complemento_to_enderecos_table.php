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
        Schema::table('enderecos', function (Blueprint $table) {
            if (!Schema::hasColumn('enderecos', 'complemento')) {
                $table->string('complemento')->nullable()->after('endereco');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enderecos', function (Blueprint $table) {
            if (Schema::hasColumn('enderecos', 'complemento')) {
                $table->dropColumn('complemento');
            }
        });
    }
};
