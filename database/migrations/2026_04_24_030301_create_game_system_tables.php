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
        // 1. Categorías (para el estilo temático)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hex_color'); // Para el neón TRON
            $table->timestamps();
        });

        // 2. Preguntas
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->json('options'); // ['A', 'B', 'C', 'D']
            $table->string('correct_answer');
            $table->integer('default_time_limit')->default(30); // Tiempo en segundos
            $table->timestamps();
        });

        // 3. Partidas
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users');
            $table->string('status')->default('waiting'); // waiting, playing, paused, finished
            $table->json('settings'); // Para guardar configuración: tiempo, modo, reglas extra
            $table->integer('current_round')->default(0);
            $table->timestamps();
        });

        // 4. Participantes (Jugadores y Equipos)
        Schema::create('game_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->string('team_name'); // Para agrupar por consenso
            $table->integer('score')->default(0);
            $table->timestamps();
        });

        // 5. Chat y Consenso
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->text('message');
            $table->boolean('is_answer')->default(false); // Flag para la propuesta final
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('game_participants');
        Schema::dropIfExists('games');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('categories');
    }
};
