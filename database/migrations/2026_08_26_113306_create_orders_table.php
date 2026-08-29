<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_price', 12, 2);
            $table->enum('payment_method', ['cod', 'transfer'])->default('cod');
            $table->enum('status', [
                'belum_dibayar',
                'diproses',
                'dikemas',
                'dalam_perjalanan',
                'selesai',
            ])->default('belum_dibayar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
