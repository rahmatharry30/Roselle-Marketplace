<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('belum_dibayar', 'diproses', 'dikemas', 'dalam_perjalanan', 'selesai', 'dibatalkan') DEFAULT 'belum_dibayar'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('belum_dibayar', 'diproses', 'dikemas', 'dalam_perjalanan', 'selesai') DEFAULT 'belum_dibayar'");
    }
};
