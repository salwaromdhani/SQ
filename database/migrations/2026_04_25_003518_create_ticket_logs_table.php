<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete(); // Lien avec le ticket
            $table->string('action'); // Ex: "status_changed"
            $table->string('old_value')->nullable(); // Ancienne valeur
            $table->string('new_value')->nullable(); // Nouvelle valeur
            $table->text('comment')->nullable(); // Note manuelle
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_logs');
    }
};