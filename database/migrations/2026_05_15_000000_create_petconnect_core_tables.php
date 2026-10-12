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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->enum('role', ['ADM', 'CLI', 'PRO'])->default('CLI');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('fotos')) {
            Schema::create('fotos', function (Blueprint $table) {
                $table->id();
                $table->string('nome_arquivo');
                $table->string('url_foto');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('especie')) {
            Schema::create('especie', function (Blueprint $table) {
                $table->id();
                $table->string('tipo_pet');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('raca')) {
            Schema::create('raca', function (Blueprint $table) {
                $table->id();
                $table->string('nome', 50)->nullable();
                $table->foreignId('id_especie')->constrained('especie')->cascadeOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pets')) {
            Schema::create('pets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
                $table->string('nome');
                $table->date('data_nascimento')->nullable();
                $table->foreignId('id_especie')->constrained('especie')->cascadeOnDelete();
                $table->foreignId('id_raca')->constrained('raca')->cascadeOnDelete();
                $table->decimal('peso', 5, 2)->nullable();
                $table->enum('sexo', ['FEMEA', 'MACHO']);
                $table->foreignId('id_foto')->nullable()->constrained('fotos')->nullOnDelete();
                $table->text('observacoes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('servicos')) {
            Schema::create('servicos', function (Blueprint $table) {
                $table->id();
                $table->string('nome')->nullable();
                $table->decimal('valor_servico', 10, 2)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('agendamentos')) {
            Schema::create('agendamentos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_user_cliente')->constrained('users')->cascadeOnDelete();
                $table->foreignId('id_pet')->constrained('pets')->cascadeOnDelete();
                $table->foreignId('id_user_sitter')->constrained('users')->cascadeOnDelete();
                $table->foreignId('id_servico')->constrained('servicos')->cascadeOnDelete();
                $table->foreignId('id_foto')->nullable()->constrained('fotos')->nullOnDelete();
                $table->dateTime('data_inicio')->nullable();
                $table->dateTime('data_fim')->nullable();
                $table->enum('status', ['pendente', 'confirmado', 'cancelado', 'concluido'])->default('pendente');
                $table->text('observacoes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pagamentos')) {
            Schema::create('pagamentos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_agendamento')->nullable()->constrained('agendamentos')->cascadeOnDelete();
                $table->decimal('total_servico', 10, 2)->nullable();
                $table->enum('metodo_pagamento', ['pix', 'cartao_credito', 'cartao_debito', 'boleto'])->nullable();
                $table->enum('status_pagamento', ['pendente', 'pago', 'estornado'])->nullable();
                $table->dateTime('data_pagamento')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('avaliacoes')) {
            Schema::create('avaliacoes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
                $table->foreignId('id_agendamento')->constrained('agendamentos')->cascadeOnDelete();
                $table->decimal('nota', 3, 2)->nullable();
                $table->text('comentarios')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('enderecos')) {
            Schema::create('enderecos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
                $table->string('endereco');
                $table->string('cidade');
                $table->string('estado');
                $table->string('cep', 20);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('disponibilidade')) {
            Schema::create('disponibilidade', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_user')->nullable()->constrained('users')->nullOnDelete();
                $table->enum('dia_semana', ['domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado'])->nullable();
                $table->time('hora_inicio')->nullable();
                $table->time('hora_fim')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('telefones')) {
            Schema::create('telefones', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
                $table->string('numero', 20);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('identificacao')) {
            Schema::create('identificacao', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
                $table->foreignId('id_foto')->constrained('fotos')->cascadeOnDelete();
                $table->enum('tipo_documento', ['CNPJ', 'CPF'])->default('CPF');
                $table->string('documento', 18)->unique()->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('historico_servicos')) {
            Schema::create('historico_servicos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_agendamento')->constrained('agendamentos')->cascadeOnDelete();
                $table->text('descricao')->nullable();
                $table->dateTime('data_evento')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('carteira')) {
            Schema::create('carteira', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_user')->nullable()->constrained('users')->nullOnDelete();
                $table->decimal('saldo', 10, 2)->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cashout')) {
            Schema::create('cashout', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_user')->nullable()->constrained('users')->nullOnDelete();
                $table->decimal('valor', 10, 2)->nullable();
                $table->enum('status', ['pendente', 'aprovado', 'recusado'])->nullable();
                $table->dateTime('data_solicitacao')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashout');
        Schema::dropIfExists('carteira');
        Schema::dropIfExists('historico_servicos');
        Schema::dropIfExists('identificacao');
        Schema::dropIfExists('telefones');
        Schema::dropIfExists('disponibilidade');
        Schema::dropIfExists('enderecos');
        Schema::dropIfExists('avaliacoes');
        Schema::dropIfExists('pagamentos');
        Schema::dropIfExists('agendamentos');
        Schema::dropIfExists('servicos');
        Schema::dropIfExists('pets');
        Schema::dropIfExists('raca');
        Schema::dropIfExists('especie');
        Schema::dropIfExists('fotos');
        Schema::dropIfExists('users');
    }
};
