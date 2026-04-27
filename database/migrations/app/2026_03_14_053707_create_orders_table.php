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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('status', ['set', 'start', 'stop', 'end'])
                ->default('set');

            $table->foreignId('from_id')
                ->nullable()
                ->constrained('coordinates')
                ->nullOnDelete();

            $table->foreignId('to_id')
                ->nullable()
                ->constrained('coordinates')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('armada_id')
                ->nullable()
                ->constrained('armadas')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
