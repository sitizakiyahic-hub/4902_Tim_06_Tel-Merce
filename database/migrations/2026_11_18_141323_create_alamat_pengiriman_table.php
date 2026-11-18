
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alamat_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')
                  ->constrained('daftar_pelanggan')
                  ->cascadeOnDelete();

            $table->string('label', 50);
            $table->string('alamat', 255);
            $table->string('kota', 100);
            $table->string('provinsi', 100);
            $table->string('kode_pos', 10);
            $table->string('status', 20);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alamat_pengiriman');
    }
};
