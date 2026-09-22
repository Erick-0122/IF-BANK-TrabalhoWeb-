<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->enum('type', ['CDB', 'CDI', 'POUPANCA']);
            $table->decimal('balance', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['account_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
